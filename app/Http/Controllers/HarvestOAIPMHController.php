<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CreativeWork;
use App\Models\ScholarlyArticle;



class HarvestOAIPMHController extends Controller

{
    // function to store file in 'upload' folder
    public function harvest(Request $request)
    {
        $myEndpoint = \Phpoaipmh\Endpoint::build($request->url);

        foreach ($myEndpoint->identify() as $identify) {
            if (!empty((string)$identify->repositoryName)) {
                $repositoryName = (string)$identify->repositoryName;
            }
        }

        if ($request->metadataFormat == "nlm") {

            if (isset($request->set)) {
                $recs = $myEndpoint->listRecords('nlm', null, null, $request->set);
            } else {
                $recs = $myEndpoint->listRecords('nlm');
            }


            foreach ($recs as $rec) {

                //print_r($rec);

                if ($rec->{'header'}->attributes()->{'status'} != "deleted") {

                    // $sha256 = hash('sha256', ''.$rec->{'header'}->{'identifier'}.'');


                    // if (!empty($_GET["repositoryName"])) {
                    //     $query["doc"]["source"] = $_GET["repositoryName"];
                    // } else {
                    //     $query["doc"]["source"] = $repositoryName;
                    // }

                    // $query["doc"]["harvester_id"] = (string)$rec->{'header'}->{'identifier'};
                    // if (isset($_GET["area"])) {
                    //     $query["doc"]["area"] = $_GET["area"];
                    // }
                    // if (isset($_GET["areaChild"])) {
                    //     $query["doc"]["areaChild"] = $_GET["areaChild"];
                    // }
                    // if (isset($_GET["corrente"])) {
                    //     $query["doc"]["corrente"] = $_GET["corrente"];
                    // }
                    // $query["doc"]["originalType"] = (string)$rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'article-categories'}->{'subj-group'}->{'subject'};
                    $record["type"] = "ScholarlyArticle";
                    $record["name"] = str_replace('"', '', (string)$rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'title-group'}->{'article-title'});
                    $record["datePublished"] = (string)$rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'pub-date'}[1]->{'year'};
                    $record["doi"] = (string)$rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'article-id'}[1];
                    $record["abstract"] = str_replace('"', '', (string)$rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'abstract'}->{'p'});

                    // Palavras-chave
                    if (isset($rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'kwd-group'}[0]->{'kwd'})) {
                        foreach ($rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'kwd-group'}[0]->{'kwd'} as $palavra_chave) {
                            $palavra_chave_corrigida = str_replace(",", ".", (string)$palavra_chave);
                            $palavra_chave_corrigida = str_replace(";", ".", (string)$palavra_chave);
                            $palavraschave_array = explode(".", $palavra_chave_corrigida);
                            foreach ($palavraschave_array  as $pc) {
                                $palavraschavepush['name'] = trim($pc);
                                $palavraschavepush['id'] = "";
                                $pcArray[] = $palavraschavepush;
                            }
                        }
                        if (isset($pcArray)) {
                            $record['about'] = $pcArray;
                        }
                        unset($pcArray);
                    }


                    foreach ($rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'contrib-group'}->{'contrib'} as $autores) {

                        if ($autores->attributes()->{'contrib-type'} == "author") {
                            $authorPush['name'] = (string)$autores->{'name'}->{'surname'} . ', ' . $autores->{'name'}->{'given-names'};
                            $authorPush['id'] = "";
                            $authorPush['function'] = "author";
                            $authorArrayNLM[] = $authorPush;
                            unset($authorPush);
                        }
                    }
                    
                    if (isset($authorArrayNLM)) {
                        $record['author'] = $authorArrayNLM;
                    }
                    unset($authorArrayNLM);
                    // $query["doc"]["numAutores"] = $i;

                    $record["isPartOf_name"] = $repositoryName;
                    // $query["doc"]["isPartOf"]["publisher"]["organization"]["name"] = (string)$rec->{'metadata'}->{'article'}->{'front'}->{'journal-meta'}->{'publisher'}->{'publisher-name'};
                    // $query["doc"]["isPartOf"]["ISSN"] = (string)$rec->{'metadata'}->{'article'}->{'front'}->{'journal-meta'}->{'issn'};
                    $record["volumeNumber"] = (string)$rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'volume'};
                    $record["issueNumber"] = (string)$rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'issue'};
                    $record["pageStart"] = (string)$rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'issue-id'};
                    // $query["doc"]["isPartOf"]["serie"] = (string)$rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'issue-title'};
                    $record["url"] = (string)$rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'self-uri'}->attributes('http://www.w3.org/1999/xlink');

                    // $query["doc"]["origin"] = "OAI-PHM";

                    // if (isset($_GET["typeOfContent"])) {
                    //     $query["doc"]["type"] = $_GET["typeOfContent"];
                    // } else {
                    //     $query["doc"]["type"] = "Artigo";
                    // }

                    // foreach ($rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'self-uri'} as $self_uri) {
                    //     $record["url"]=(string)$self_uri->attributes('http://www.w3.org/1999/xlink');
                    //     break;
                    // }

                    // //print_r($query);

                    //$id = DB::table('creative_works')->insertGetId($record);

                    $article = new ScholarlyArticle($record);
                    $cw = CreativeWork::find($request->periodicalID);
                    $createArticleResult = $cw->hasPart()->save($article);

                    unset($record);
                    flush();

                }
            }

        } elseif ($request->metadataFormat == "oai_dc") {

            if (isset($request->set)) {
                $recs = $myEndpoint->listRecords('oai_dc', null, null, $request->set);
            } else {
                $recs = $myEndpoint->listRecords('oai_dc');
            }
            foreach ($recs as $rec) {
                if (!empty($rec->metadata)) {
                    $data = $rec->metadata->children('http://www.openarchives.org/OAI/2.0/oai_dc/');
                    if (!empty($data)) {
                        $rows = $data->children('http://purl.org/dc/elements/1.1/');
                    }

                    //var_dump ($rows);

                    // if (isset($rows->publisher)) {
                    //     $query["doc"]["isPartOf"]["publisher"]["organization"]["name"] = (string)$rows->publisher;
                    // }

                    $record["type"] = "ScholarlyArticle";

                    if (isset($rows->title)) {
                        $record["name"] = (string)$rows->title[0];
                        if (isset($rows->title[1])) {
                            $record["translationOfWork"] = (string)$rows->title[1];
                        }
                    }

                    if (isset($rows->identifier)) {
                        $identifierString = (string)$rows->identifier[1];
                        if (substr($identifierString, 0, 2) == "10") {
                            $record["doi"] = (string)$rows->identifier[1];
                        }
                    }

                    if (isset($rows->identifier)) {
                        if (substr((string)$rows->identifier, 0, 4) === "http") {
                            $record["url"] = (string)$rows->identifier;
                        }
                    }

                    // if (isset($rows->identifier)) {
                    //     if (substr((string)$rows->identifier, 0, 4) === "http"){
                    //         $query["doc"]["relation"][] = (string)$rows->identifier;
                    //     }
                    // }

                    if (isset($rows->description)) {
                        $record["abstract"] = (string)$rows->description[0];
                    }


                    $record["isPartOf_name"] = $repositoryName;

                    if (isset($rows->source)) {
                        $record["isPartOf_citation"] = (string)$rows->source;
                    }

                    if (isset($rows->subject)) {
                        $subjectString = (string)$rows->subject;
                        $subjectArray = explode(";", $subjectString);
                        if (is_array($subjectArray)) {
                            foreach ($subjectArray as $sub) {
                                $palavraschavepush['name'] = trim($sub);
                                $palavraschavepush['id'] = "";
                                $pcArray[] = $palavraschavepush;
                            }
                        } else {
                            $palavraschavepush['name'] = trim($subjectArray);
                            $palavraschavepush['id'] = "";
                            $pcArray[] = $palavraschavepush;
                        }

                        if (isset($rows->subject[1])) {
                            $subjectString = (string)$rows->subject[1];
                            $subjectArray = explode(";", $subjectString);
                            if (is_array($subjectArray)) {
                                foreach ($subjectArray as $sub) {
                                    $palavraschavepush['name'] = trim($sub);
                                    $palavraschavepush['id'] = "";
                                    $pcArray[] = $palavraschavepush;
                                }
                            } else {
                                $palavraschavepush['name'] = trim($subjectArray);
                                $palavraschavepush['id'] = "";
                                $pcArray[] = $palavraschavepush;
                            }
                        }

                        if (isset($rows->subject[2])) {
                            $subjectString = (string)$rows->subject[2];
                            $subjectArray = explode(";", $subjectString);
                            if (is_array($subjectArray)) {
                                foreach ($subjectArray as $sub) {
                                    $palavraschavepush['name'] = trim($sub);
                                    $palavraschavepush['id'] = "";
                                    $pcArray[] = $palavraschavepush;
                                }
                            } else {
                                $palavraschavepush['name'] = trim($subjectArray);
                                $palavraschavepush['id'] = "";
                                $pcArray[] = $palavraschavepush;
                            }
                        }
                        if (isset($pcArray)) {
                            $record['about'] = $pcArray;
                        }
                        unset($pcArray);
                    }

                    if (isset($rows->creator)) {
                        $i = 0;
                        foreach ($rows->creator as $author) {
                            $authorArray = explode(";", (string)$author);
                            $authorPush['name'] = $authorArray[0];
                            $authorPush['id'] = "";
                            $authorPush['function'] = "author";
                            $d[] = $authorPush;
                            //$query["doc"]["author"][$i]["person"]["name"] = $authorArray[0];
                            // if (!empty($authorArray[1])) {
                            //     if ($_GET["useTematres"] == true) {
                            //         $resultTematres = Authorities::tematresQuery(trim(strip_tags($authorArray[1])), $tematres_url);
                            //         if ($resultTematres['foundTerm'] != "ND") {
                            //             $query["doc"]["author"][$i]["organization"]["name"] = $resultTematres['foundTerm'];
                            //             $query["doc"]["author"][$i]["organization"]["tematres"] = true;
                            //         } else {
                            //             $query["doc"]["author"][$i]["organization"]["name"] = trim(strip_tags($authorArray[1]));
                            //         }
                            //     } else {
                            //         $query["doc"]["author"][$i]["organization"]["name"] = trim(strip_tags($authorArray[1]));
                            //     }
                            // }
                            $i++;
                        }
                        if (isset($d)) {
                            $record['author'] = $d;
                        }
                        unset($d);
                    }
                    //$record["numAutores"] = $i;

                    if (isset($rows->date)) {
                        $record['datePublished'] = substr((string)$rows->date, 0, 4);
                    }

                    // if (isset($rows->relation)) {
                    //     $query["doc"]["relation"][] = (string)$rows->relation;
                    // }

                    $id = (string)$rec->header->identifier;
                    //$query["doc"]["id"] = (string)$rec->header->identifier;

                    // if (!empty($_GET["repositoryName"])) {
                    //     $query["doc"]["source"] = $_GET["repositoryName"];
                    // } else {
                    //     $query["doc"]["source"] = $repositoryName;
                    // }
                    // $query["doc"]["origin"] = "OAI-PHM";
                    // if (isset($_GET["typeOfContent"])) {
                    //     $query["doc"]["type"] = $_GET["typeOfContent"];
                    // } else {
                    //     $query["doc"]["type"] = "Artigo";
                    // }

                    // print_r($query);
                    // echo "<br/>";
                    //print_r(json_encode($query["doc"]));
                    //$resultado = $this->createRecord(json_encode($query["doc"]));

                    //$id = DB::table('creative_works')->insertGetId($record);


                    $article = new ScholarlyArticle($record);
                    $cw = CreativeWork::find($request->periodicalID);
                    $createArticleResult = $cw->hasPart()->save($article);


                    //print_r($resultado);
                    //print_r($query);
                    unset($record);

                }

            }

        } elseif ($request->metadataFormat == "rfc1807") {

            $recs = $myEndpoint->listRecords('rfc1807');
            foreach ($recs as $rec) {
                if ($rec->{'header'}->attributes()->{'status'} != "deleted") {

                    //var_dump($rec);

                    // $sha256 = hash('sha256', ''.$rec->{'header'}->{'identifier'}.'');

                    // if (!empty($_GET["repositoryName"])) {
                    //     $query["doc"]["source"] = $_GET["repositoryName"];
                    // } else {
                    //     $query["doc"]["source"] = $repositoryName;
                    // }

                    // $query["doc"]["set"] = (string)$rec->{'header'}->{'setSpec'};
                    // $query["doc"]["harvester_id"] = (string)$rec->{'header'}->{'identifier'};
                    // if (isset($_GET["area"])) {
                    //     $query["doc"]["area"] = $_GET["area"];
                    // }
                    // if (isset($_GET["areaChild"])) {
                    //     $query["doc"]["areaChild"] = $_GET["areaChild"];
                    // }
                    // if (isset($_GET["corrente"])) {
                    //     $query["doc"]["corrente"] = $_GET["corrente"];
                    // }
                    // $query["doc"]["originalType"] = (string)$rec->{'metadata'}->{'rfc1807'}->{'type'}[0];
                    $record["name"] = str_replace('"', '', (string)$rec->{'metadata'}->{'rfc1807'}->{'title'});
                    $record["datePublished"] = substr((string)$rec->{'metadata'}->{'rfc1807'}->{'date'}, 0, 4);
                    // //$query["doc"]["doi"] = (string)$rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'article-id'}[1];
                    $record["abstract"] = str_replace('"', '', (string)$rec->{'metadata'}->{'rfc1807'}->{'abstract'});

                    // // Palavras-chave
                    // if (isset($rec->{'metadata'}->{'rfc1807'}->{'keyword'})) {
                    //     foreach ($rec->{'metadata'}->{'rfc1807'}->{'keyword'} as $palavra_chave) {
                    //         $pc_array = [];
                    //         $pc_array = explode(";", (string)$palavra_chave);
                    //         foreach ($pc_array as $pc) {
                    //             $query["doc"]["about"][] = trim($pc);
                    //         }
                    //     }
                    // }


                    // $i = 0;
                    // foreach ($rec->{'metadata'}->{'rfc1807'}->{'author'} as $autor) {
                    //     $autor_array = explode(";", (string)$autor);
                    //     $autor_nome_array = explode(",", (string)$autor_array[0]);
                    //     $query["doc"]["author"][$i]["person"]["completeName"] = $autor_nome_array[1].' '.ucwords(strtolower($autor_nome_array[0]));
                    //     $query["doc"]["author"][$i]["person"]["name"] = (string)$autor_array[0];
                    //     if (isset($autor_array[1])) {
                    //         if ($_GET["useTematres"] == true) {
                    //             $resultTematres = Authorities::tematresQuery(trim(strip_tags((string)$autor_array[1])), $tematres_url);
                    //             if ($resultTematres['foundTerm'] != "ND") {
                    //                 $query["doc"]["author"][$i]["organization"]["name"] = $resultTematres['foundTerm'];
                    //                 $query["doc"]["author"][$i]["organization"]["tematres"] = true;
                    //             } else {
                    //                 $query["doc"]["author"][$i]["organization"]["name"] = trim(strip_tags((string)$autor_array[1]));
                    //             }
                    //         } else {
                    //             $query["doc"]["author"][$i]["organization"]["name"] = trim(strip_tags((string)$autor_array[1]));
                    //         }
                    //     }
                    //     $i++;
                    // }
                    // $query["doc"]["numAutores"] = $i;

                    // $query["doc"]["isPartOf"]["name"] = $repositoryName;
                    // //$query["doc"]["artigoPublicado"]["nomeDaEditora"] = (string)$rec->{'metadata'}->{'article'}->{'front'}->{'journal-meta'}->{'publisher'}->{'publisher-name'};
                    // //$query["doc"]["artigoPublicado"]["issn"] = (string)$rec->{'metadata'}->{'article'}->{'front'}->{'journal-meta'}->{'issn'};
                    // //$query["doc"]["artigoPublicado"]["volume"] = (string)$rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'volume'};
                    // //$query["doc"]["artigoPublicado"]["fasciculo"] = (string)$rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'issue'};
                    // //$query["doc"]["artigoPublicado"]["paginaInicial"] = (string)$rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'issue-id'};
                    // //$query["doc"]["artigoPublicado"]["serie"] = (string)$rec->{'metadata'}->{'article'}->{'front'}->{'article-meta'}->{'issue-title'};
                    // $query["doc"]["url"] = (string)$rec->{'metadata'}->{'rfc1807'}->{'id'};


                    // $query["doc"]["relation"][]=(string)$rec->{'metadata'}->{'rfc1807'}->{'id'};

                    // $query["doc"]["origin"] = "OAI-PHM";
                    // if (isset($_GET["typeOfContent"])) {
                    //     $query["doc"]["type"] = $_GET["typeOfContent"];
                    // } else {
                    //     $query["doc"]["type"] = "Artigo";
                    // }
                    // $query["doc_as_upsert"] = true;

                    // $resultado = Elasticsearch::update($sha256, $query);
                    // print_r($resultado);

                    $id = DB::table('creative_works')->insertGetId($record);

                    unset($record);
                    flush();

                }
            }
        } else {
            echo "Formato de metadados não definido"; 
        }
    }
}