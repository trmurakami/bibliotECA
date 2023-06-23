<template>
    <div>
        <h1>Editor</h1>
        <div class="alert alert-success" role="alert" v-if="success">
            Registro criado com sucesso! - <a href="/editor" class="alert-link">Criar novo registro</a>.
        </div>

        <div class="alert alert-success" role="alert" v-if="successUpdate">
            Registro atualizado com sucesso! - <a href="/editor" class="alert-link">Criar novo registro</a>.
        </div>

        <h2>Enviar imagem de capa capa</h2>

        <div class="input-group mb-3">
            <input type="file" @change="handleFileChange" class="form-control" accept=".jpg,.gif,.png">
        </div>

        <div class="row">
            <div class="col d-flex justify-content-center align-items-center">
                <img :src="record.base64Image" v-if="record.base64Image" height="300">
            </div>
            <div class="col d-flex justify-content-center align-items-center">
                <button class="btn btn-danger" @click="record.base64Image = ''" v-if="record.base64Image">Excluir
                    imagem</button>
            </div>
        </div>

        <h2>Metadados</h2>

        <form @submit.prevent>
            <input type="hidden" name="_token" :value="csrf" />

            <!-- Type -->

            <label for="type" class="form-label mb-3">Tipo de material</label>
            <select class="form-select mb-3" id="type" v-model="record.type" name="type" required>
                <option value="Livro" selected>Livro</option>
                <option value="Artigo">Artigo</option>
                <option value="Trabalho em Evento">Trabalho em Evento</option>
                <option value="Filme">Filme</option>
                <option value="Gravação musical">Gravação musical</option>
                <option value="Álbum musical">Álbum musical</option>
                <option value="Vídeo">Vídeo</option>
                <option value="Periódico">Periódico</option>
            </select>

            <div class="m-5">
                <h5>Identificadores:</h5>

                <!-- DOI -->
                <div class="alert alert-warning" role="alert" v-if="loadingDOI">
                    Buscando dados do DOI na Crossref ...
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text">DOI</span>
                    <input type="text" class="form-control" placeholder="Digite o DOI" aria-label="Digite o DOI"
                        v-model="record.doi" id="doi" name="doi" aria-describedby="doi">
                    <button class="btn btn-info" type="button" id="doi"
                        @click="getDOI(record.doi), (loadingDOI = true)">Recuperar dados de DOI na Crossref</button>
                </div>


                <!-- ISSN -->
                <template v-if="record.type === 'Periódico'">
                    <div class="input-group mb-3">
                        <span class="input-group-text">ISSN</span>
                        <input type="text" class="form-control" v-model="record.issn" id="ISSN" name="ISSN"
                            placeholder="Digite o ISSN" />
                    </div>
                </template>

                <!-- EIDR -->
                <div class="d-flex align-items-center mb-3 mt-3" v-if="loadingEIDR">
                    <strong>Carregando dados da base EIDR ...</strong>
                    <div class="spinner-border ms-auto" role="status" aria-hidden="true"></div>
                </div>
                <template v-if="record.type === 'Filme'">
                    <div class="input-group mb-3">
                        <span class="input-group-text">EIDR</span>
                        <input type="text" class="form-control" v-model="record.titleEIDR" id="titleEIDR" name="titleEIDR"
                            placeholder="Digite o EIDR" />
                        <a class="btn btn-warning btn-sm" href="https://ui.eidr.org/search" target="_blank">EIDR pode ser
                            consultado neste link</a>
                        <button class="btn btn-info btn-sm" @click="
                            getEIDR(record.titleEIDR.replace('10.5240/', ''))
                            ">
                            Recuperar dados do EIDR
                        </button>
                    </div>
                </template>


                <!-- OAI-PMH -->
                <template v-if="record.type === 'Periódico'">
                    <div class="input-group mb-3">
                        <span class="input-group-text">OAI-PMH</span>
                        <input type="text" class="form-control" v-model="record.oaipmh" id="oaipmh" name="oaipmh"
                            placeholder="Digite a URL do OAI-PMH" />
                        <button class="btn btn-info btn-sm" @click="
                            getOAIPMH(record.oaipmh),
                            getOAIMetadataFormats(record.oaipmh),
                            getOAISets(record.oaipmh)
                            ">
                            Recuperar dados do OAI-PMH
                        </button>
                    </div>
                </template>

                <!-- OAI-PMH MetadataFormats-->
                <template v-if="OAIMetadataFormats">
                    <div class="m-3">
                        <label for="isbn" class="form-label">Formato de metadados OAI-PMH (Escolha um)</label>
                        <select class="form-select" v-model="record.oaimetadataformat">
                            <option v-for="metadataFormat in OAIMetadataFormats
                                .ListMetadataFormats.metadataFormat" :key="metadataFormat.metadataPrefix"
                                :value="metadataFormat.metadataPrefix">
                                {{ metadataFormat.metadataPrefix }}
                            </option>
                        </select>
                    </div>
                </template>

                <!-- OAI-PMH Sets-->
                <template v-if="OAISets">
                    <div class="m-3">
                        <label for="isbn" class="form-label">Sets OAI-PMH</label>
                        <select class="form-select" v-model="record.oaiset">
                            <option value="">Nenhum</option>
                            <option v-for="set in OAISets.ListSets.set" :key="set.setSpec" :value="set.setSpec">
                                {{ set.setName }}
                            </option>
                        </select>
                    </div>
                </template>

                <!-- ISBN -->
                <template v-if="record.type === 'Livro'">
                    <div class="alert alert-warning" role="alert" v-if="loadingISBN">
                        Buscando dados no Google Books ...
                    </div>
                    <div class="alert alert-warning" role="alert" v-if="loadingZ3950">
                        Buscando dados no Z39.50 ...
                    </div>
                    <div class="input-group mb-3" v-for="(isbn, indexisbn) in record.isbn">
                        <span class="input-group-text">ISBN</span>
                        <input type="text" class="form-control" v-model.trim="isbn.id" id="isbn" name="isbn"
                            placeholder="Digite o ISBN" />
                        <select class="form-select" v-model="isbn.type">
                            <option value="ISBN-13">ISBN-13</option>
                            <option value="ISBN-10">ISBN-10</option>
                            <option value="canceled/invalid">
                                Cancelado ou inválido
                            </option>
                        </select>
                        <button @click="deleteField('isbn', indexisbn)" class="btn btn-danger btn-sm">
                            Apagar
                        </button>
                        <button class="btn btn-info btn-sm" @click="
                            getISBN(record.isbn[indexisbn].id),
                            (loadingISBN = true)
                            " v-if="record.isbn[indexisbn].id != ''">
                            Google Books
                        </button>
                        <button class="btn btn-info btn-sm" @click="
                            getZ3950(
                                record.isbn[indexisbn].id,
                                'dedalus.usp.br:9991/usp01',
                                'USP/DEDALUS'
                            ),
                            getZ3950(
                                record.isbn[indexisbn].id,
                                'unesp.alma.exlibrisgroup.com:1921/55UNESP_INST',
                                'UNESP'
                            ),
                            getZ3950(
                                record.isbn[indexisbn].id,
                                '162.214.168.248:9998/bib',
                                'BN'
                            ),
                            (loadingZ3950 = true)
                            " v-if="record.isbn[indexisbn].id != ''">
                            Z39.50
                        </button>
                    </div>

                    <button @click="addField('isbn')" class="btn btn-info btn-sm mb-2">
                        + ISBN
                    </button>
                </template>

                <template v-if="Z3950Records">
                    <div class="alert alert-info alert-dismissible fade show bg-opacity-10" role="alert">
                        <table class="table p-2 text-dark">
                            <thead>
                                <tr>
                                    <th scope="col">Fonte</th>
                                    <th scope="col">Título</th>
                                    <th scope="col">Autor</th>
                                    <th scope="col">Outros autores</th>
                                    <th scope="col">Editora</th>
                                    <th scope="col">Local</th>
                                    <th scope="col">Data de publicação</th>
                                    <th scope="col">Descrição física</th>
                                    <th scope="col">Idioma</th>
                                    <th scope="col">Edição</th>
                                    <!-- <th scope="col">Registro completo</th> -->
                                    <th scope="col">Usar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(
                                    Z3950Record, indexZ3950Record
                                ) in Z3950Records" :key="indexZ3950Record">
                                    <th scope="row">{{ Z3950Record.source }}</th>
                                    <td>{{ Z3950Record.title }}</td>
                                    <td>{{ Z3950Record.author }}</td>
                                    <td>{{ Z3950Record.editor }}</td>
                                    <td>{{ Z3950Record.publisher }}</td>
                                    <td>{{ Z3950Record.pub_place }}</td>
                                    <td>{{ Z3950Record.pub_date }}</td>
                                    <td>{{ Z3950Record.extent }}</td>
                                    <td>{{ Z3950Record.language }}</td>
                                    <td>{{ Z3950Record.edition }}</td>
                                    <!-- <td>{{ Z3950Record }}</td> -->
                                    <td>
                                        <button class="btn btn-info btn-sm m-2" @click="
                                            (record.name = Z3950Record.title),
                                            addAuthor(Z3950Record.author),
                                            addAuthor(Z3950Record.editor),
                                            (record.datePublished =
                                                Z3950Record.pub_date),
                                            (record.copyrightYear =
                                                Z3950Record.pub_date),
                                            (record.publisher[0].name =
                                                Z3950Record.publisher),
                                            (record.bookEdition =
                                                Z3950Record.edition),
                                            (record.numberOfPages =
                                                Z3950Record.extent)
                                            ">
                                            Usar
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="button" class="btn-close" aria-label="Close" @click="Z3950Records = null"></button>
                    </div>
                </template>




            </div>


            <!-- Name -->
            <div class="form-floating mb-2">
                <input type="text" class="form-control" v-model.trim="record.name" id="name" name="name"
                    placeholder="Digite o título" :class="{ 'is-invalid': Object.keys(record.name).length === 0, }" />
                <label for="name">Título</label>
            </div>

            <!-- Translation of Work -->
            <template v-if="record.type === 'Livro'">
                <div class="form-floating mb-2">
                    <input type="text" class="form-control" v-model.trim="record.translationOfWork" id="translationOfWork"
                        name="translationOfWork" placeholder="A obra da qual esta obra foi traduzida" />
                    <label for="translationOfWork">Nome da Obra original</label>
                </div>
            </template>

            <!-- alternateName -->
            <template v-if="record.type === 'Livro'">
                <div class="form-floating mb-2">
                    <input type="text" class="form-control" v-model.trim="record.alternateName" id="alternateName"
                        name="alternateName" placeholder="Nome alternativo" />
                    <label for="alternateName">Nome alternativo</label>
                </div>
            </template>


            <!-- Author -->
            <template v-if="record.type === 'Livro' ||
                record.type === 'Gravação musical' ||
                record.type === 'Álbum musical' ||
                record.type === 'Artigo' ||
                record.type === 'Trabalho em Evento'
                ">
                <div class="input-group mb-2" v-for="(author, indexAuthor) in record.author">
                    <span class="input-group-text">Autor / Função</span>
                    <input v-model.trim="author.id" autocomplete="off" type="text" aria-label="Author ID"
                        class="form-control" placeholder="ID do autor" id="id" readonly />
                    <input v-model.trim="author.viaf" autocomplete="off" type="text" aria-label="VIAF" class="form-control"
                        placeholder="VIAF" id="viaf" />
                    <input hidden v-model.trim="author.id_lattes13" autocomplete="off" type="text" aria-label="ID Lattes"
                        class="form-control" placeholder="ID Lattes" id="id_lattes13" readonly />
                    <!-- <input
                        v-model="author.name"
                        autocomplete="off"
                        type="text"
                        class="form-control"
                        placeholder="Digite o nome"
                    /> -->
                    <input class="form-control" list="datalistAuthority" placeholder="Digite o nome"
                        v-model="author.name" />
                    <select v-model="author.function" autocomplete="off" class="form-select" aria-label="Author funcion"
                        placeholder="Função">
                        <option value="Autor" selected>Autor</option>
                        <option value="Organizador" selected>Organizador</option>
                        <option value="Autor do prefácio" selected>
                            Autor do prefácio
                        </option>
                    </select>
                    <button @click="deleteField('author', indexAuthor)" class="btn btn-danger btn-sm">
                        Limpar
                    </button>
                </div>

                <button @click="addField('author')" class="btn btn-info btn-sm mb-2">
                    Adicionar autor
                </button>
            </template>

            <!-- Director -->
            <template v-if="record.type === 'Filme' || record.type === 'Vídeo'">
                <div class="input-group mb-2" v-for="(director, indexDirector) in record.director">
                    <span class="input-group-text">Diretor</span>
                    <input v-model="director.id" autocomplete="off" type="text" aria-label="Diretor ID" class="form-control"
                        name="directorId" id="directorId" placeholder="ID do diretor" readonly />
                    <input v-model.trim="director.name" autocomplete="off" type="text" class="form-control"
                        name="directorName" id="directorName" placeholder="Nome" list="datalistAuthority" @input="
                            getIDAuthority(director),
                            getAuthorities(director.name)
                            " />
                    <datalist id="datalistAuthority">
                        <option v-for="authority in authorities" :value="authority.name" :key="authority.id"
                            :id="authority.id"></option>
                    </datalist>
                    <button @click="deleteField('director', indexDirector)" class="btn btn-danger btn-sm">
                        Limpar
                    </button>
                </div>

                <button @click="addField('director')" class="btn btn-info btn-sm mb-2">
                    Adicionar diretor
                </button>
            </template>

            <!-- Actor -->
            <template v-if="record.type === 'Filme' || record.type === 'Vídeo'">
                <div class="input-group mb-2" v-for="(actor, indexActor) in record.actor">
                    <span class="input-group-text">Ator/Atriz</span>
                    <input v-model="actor.id" autocomplete="off" type="text" class="form-control" name="idActor"
                        id="idActor" placeholder="ID do ator / atriz" readonly />
                    <input v-model.trim="actor.name" autocomplete="off" type="text" class="form-control" name="nameActor"
                        id="nameActor" placeholder="Nome do ator / atriz" @input="
                            getIDAuthority(actor), getAuthorities(actor.name)
                            " list="datalistAuthority" />
                    <datalist id="datalistAuthority">
                        <option v-for="authority in authorities" :value="authority.name" :key="authority.id"
                            :id="authority.id"></option>
                    </datalist>
                    <button @click="deleteField('actor', indexActor)" class="btn btn-danger btn-sm">
                        Limpar
                    </button>
                </div>

                <button @click="addField('actor')" class="btn btn-info btn-sm mb-2">
                    Adicionar ator
                </button>
            </template>

            <!-- Translator -->
            <template v-if="record.type === 'Livro'">
                <div class="input-group mb-2" v-for="(translator, indexTranslator) in record.translator">
                    <span class="input-group-text">Tradutor</span>
                    <input v-model="translator.id" autocomplete="off" type="text" class="form-control" name="id" id="id"
                        placeholder="ID do tradutor" readonly />
                    <input v-model.trim="translator.name" list="datalistAuthority" autocomplete="off" type="text"
                        class="form-control" name="name" id="name" placeholder="Nome do tradutor(a)" @input="
                            getIDAuthority(translator),
                            getAuthorities(translator.name)
                            " />
                    <datalist id="datalistAuthority">
                        <option v-for="authority in authorities" :value="authority.name" :key="authority.id"
                            :id="authority.id"></option>
                    </datalist>
                    <button @click="deleteField('translator', indexTranslator)" class="btn btn-danger btn-sm">
                        Limpar
                    </button>
                </div>

                <button @click="addField('translator')" class="btn btn-info btn-sm mb-2">
                    Adicionar tradutor
                </button>
            </template>

            <!-- Music By -->
            <template v-if="record.type === 'Filme'">
                <div class="input-group mb-2" v-for="(musicby, indexMusicBy) in record.musicby">
                    <span class="input-group-text">Música por</span>
                    <input v-model="musicby.id" type="text" class="form-control" name="idMusicBy" id="idMusicBy"
                        placeholder="ID do Artista" readonly />
                    <input v-model.trim="musicby.name" type="text" class="form-control" name="nameMusicBy" id="nameMusicBy"
                        placeholder="Nome do Artista" list="datalistAuthority" @input="
                            getIDAuthority(musicby),
                            getAuthorities(musicby.name)
                            " />
                    <datalist id="datalistAuthority">
                        <option v-for="authority in authorities" :value="authority.name" :key="authority.id"
                            :id="authority.id"></option>
                    </datalist>
                    <button @click="deleteField('musicby', indexMusicBy)" class="btn btn-danger btn-sm">
                        Limpar
                    </button>
                </div>

                <button @click="addField('musicby')" class="btn btn-info btn-sm mb-2">
                    Adicionar Música por
                </button>
            </template>

            <!-- Abstract -->
            <div class="form-floating mb-2">
                <textarea class="form-control" placeholder="Digite o resumo" v-model.trim="record.abstract" id="abstract"
                    name="abstract" style="height: 100px"></textarea>
                <label for="abstract">Resumo</label>
            </div>

            <!-- Description -->
            <div class="form-floating mb-2">
                <textarea class="form-control" placeholder="Digite uma descrição" v-model.trim="record.description"
                    id="description" name="description" style="height: 100px"></textarea>
                <label for="description">Descrição</label>
            </div>


            <!-- Date Published -->
            <div class="form-floating mb-2">
                <input type="text" class="form-control" v-model.trim="record.datePublished" id="datePublished"
                    name="datePublished" placeholder="Digite a data de publicação" />
                <label for="datePublished">Data de publicação</label>
            </div>

            <!-- countryOfOrigin -->
            <template v-if="record.type === 'Filme' ||
                record.type === 'Livro' ||
                record.type === 'Álbum musical'
                ">
                <div class="input-group mb-2">
                    <span class="input-group-text">País de origem</span>
                    <select class="form-select" v-model="record.countryOfOrigin">
                        <option value="Brasil">Brasil</option>
                        <option value="Espanha">Espanha</option>
                        <option value="Estados Unidos">Estados Unidos</option>
                        <option value="França">França</option>
                        <option value="Inglaterra">Inglaterra</option>
                        <option value="Portugal">Portugal</option>
                    </select>
                </div>
            </template>

            <!-- inlanguage -->
            <div class="input-group mb-2">
                <span class="input-group-text">Idioma</span>
                <select class="form-select" v-model="record.inLanguage">
                    <option value="Português">Português</option>
                    <option value="Inglês">Inglês</option>
                    <option value="Francês">Francês</option>
                    <option value="Espanhol">Espanhol</option>
                </select>
            </div>

            <!-- Production Company -->
            <template v-if="record.type === 'Filme' || record.type === 'Vídeo'">
                <div class="input-group mb-2"
                    v-for="(productionCompany, indexProductionCompany) in record.productionCompany">
                    <span class="input-group-text">Produtora</span>
                    <input v-model="productionCompany.id" type="text" class="form-control" name="id" id="id"
                        placeholder="ID da Produtora" readonly />
                    <input v-model="productionCompany.name" type="text" class="form-control" name="name" id="name"
                        placeholder="Nome da Produtora" list="datalistAuthorityOrg" @input="
                            getIDAuthority(productionCompany),
                            getAuthoritiesOrganization(
                                productionCompany.name
                            )
                            " />
                    <datalist id="datalistAuthorityOrg">
                        <option v-for="authority in authoritiesOrganization" :value="authority.name" :key="authority.id"
                            :id="authority.id"></option>
                    </datalist>
                    <button @click="
                        deleteField(
                            'productionCompany',
                            indexProductionCompany
                        )
                        " class="btn btn-danger btn-sm">
                        Limpar
                    </button>
                </div>
                <button @click="addField('productionCompany')" class="btn btn-info btn-sm mb-2">
                    Adicionar produtora
                </button>
            </template>

            <!-- isrcCode -->
            <template v-if="record.type === 'Gravação musical'">
                <div class="form-floating mb-2">
                    <input type="text" class="form-control" v-model="record.isrcCode" id="isrcCode" name="isrcCode"
                        placeholder="Digite o código ISRC" />
                    <label for="isrcCode">Código ISRC</label>
                    <div id="isrcCode" class="form-text">
                        <a href="https://isrcsearch.ifpi.org/#!/search" target="_blank">ISRC pode ser consultado neste
                            link</a>
                    </div>
                </div>
            </template>

            <!-- recordingOf -->
            <template v-if="record.type === 'Gravação musical'">
                <div class="form-floating mb-2">
                    <input type="text" class="form-control" v-model="record.recordingOf" id="recordingOf" name="recordingOf"
                        placeholder="Digite a gravação original em que esta se baseou" />
                    <label for="recordingOf">Gravação de</label>
                </div>
            </template>

            <!-- byartist -->
            <template v-if="record.type === 'Gravação musical' ||
                record.type === 'Álbum musical'
                ">
                <div class="input-group mb-2" v-for="(byartist, indexArtist) in record.byartist">
                    <span class="input-group-text">Artista</span>
                    <input v-model="byartist.id" autocomplete="off" type="text" aria-label="ByArtist ID"
                        class="form-control" id="id" placeholder="ID do Artista" readonly />
                    <input v-model="byartist.name" autocomplete="off" type="text" aria-label="ByArtist Name"
                        class="form-control" id="name" placeholder="Nome do Artista" list="datalistArtist" @input="
                            getIDAuthority(byartist),
                            getAuthorities(byartist.name)
                            " />
                    <datalist id="datalistArtist">
                        <option v-for="authorityArtist in authorities" :value="authorityArtist.name"
                            :key="authorityArtist.id" :id="authorityArtist.id" :function="authorityArtist.function">
                        </option>
                    </datalist>
                    <input v-model="byartist.function" autocomplete="off" type="text" aria-label="ByArtist Function"
                        class="form-control" id="function" placeholder="Função" />
                    <button @click="deleteField('byartist', indexArtist)" class="btn btn-danger btn-sm">
                        Limpar
                    </button>
                </div>
                <br />
                <button @click="addField('byartist')" class="btn btn-info btn-sm mb-2">
                    Adicionar artista
                </button>
            </template>

            <!-- inAlbum -->
            <template v-if="record.type === 'Gravação musical'">
                <div class="input-group mb-2" v-for="(inAlbum, indexinAlbum) in record.inAlbum">
                    <span class="input-group-text">Álbum</span>
                    <input v-model="inAlbum.id" type="text" class="form-control" name="id" id="id" placeholder="ID do Álbum"
                        readonly />
                    <input v-model="inAlbum.name" type="text" class="form-control" name="name" id="name"
                        placeholder="Nome do Álbum" list="datalistAuthorityOrg" @input="
                            getIDAuthority(inAlbum),
                            getAuthoritiesOrganization(inAlbum.name)
                            " />
                    <datalist id="datalistAuthorityOrg">
                        <option v-for="authority in authoritiesOrganization" :value="authority.name" :key="authority.id"
                            :id="authority.id"></option>
                    </datalist>
                    <button @click="deleteField('inAlbum', indexinAlbum)" class="btn btn-danger btn-sm">
                        Limpar
                    </button>
                </div>
                <button @click="addField('inAlbum')" class="btn btn-info btn-sm mb-2">
                    Adicionar álbum
                </button>
            </template>

            <!-- Publisher -->
            <div class="input-group mb-2" v-for="(publisher, indexpublisher) in record.publisher">
                <span class="input-group-text">Editora / Publicadora</span>
                <input v-model.trim="publisher.name" type="text" class="form-control" name="name" id="name"
                    placeholder="Nome da Editora / Publicadora" list="datalistAuthorityOrg"
                    :class="{ 'is-invalid': Object.keys(publisher.name).length === 0, }" />
                <button @click="deleteField('publisher', indexpublisher)" class="btn btn-danger btn-sm">
                    Limpar
                </button>
            </div>
            <button @click="addField('publisher')" class="btn btn-info btn-sm mb-2">
                Adicionar editora / publicadora
            </button>

            <!-- subtitleLanguage -->
            <template v-if="record.type === 'Filme' || record.type === 'Vídeo'">
                <div class="input-group mb-2" v-for="(
                        subtitleLanguage, indexSubtitleLanguage
                    ) in record.subtitleLanguage">
                    <span class="input-group-text">Idioma da legenda</span>
                    <select class="form-select" v-model="subtitleLanguage.code">
                        <option value="pt-BR">Português</option>
                        <option value="en-US">Inglês (Estados Unidos)</option>
                        <option value="fr-FR">Francês</option>
                        <option value="es-ES">Espanhol</option>
                    </select>
                    <button @click="
                        deleteField(
                            'subtitleLanguage',
                            indexSubtitleLanguage
                        )
                        " class="btn btn-danger btn-sm">
                        Limpar
                    </button>
                </div>

                <button @click="addField('subtitleLanguage')" class="btn btn-info btn-sm mb-2">
                    Adicionar idioma da legenda
                </button>
            </template>

            <!-- Book Edition -->
            <template v-if="record.type === 'Livro'">
                <div class="form-floating mb-2">
                    <input type="text" class="form-control" v-model="record.bookEdition" id="bookEdition" name="bookEdition"
                        placeholder="Digite a edição" />
                    <label for="bookEdition">Edição</label>
                </div>
            </template>

            <!-- Number of pages -->
            <template v-if="record.type === 'Livro'">
                <div class="form-floating mb-2">
                    <input type="text" class="form-control" v-model="record.numberOfPages" id="numberOfPages"
                        name="numberOfPages" placeholder="Digite o número de páginas" />
                    <label for="numberOfPages">Número de paginas</label>
                </div>
            </template>

            <!-- Duration -->
            <template v-if="record.type === 'Filme' ||
                record.type === 'Vídeo' ||
                record.type === 'Gravação musical'
                ">
                <div class="form-floating mb-2">
                    <input type="text" class="form-control" v-model="record.duration" id="duration" name="duration"
                        placeholder="Digite a duração" />
                    <label for="duration">Duração</label>
                </div>
            </template>


            <div class="d-flex bd-highlight mt-2">
                <div class="p-2 flex-grow-1 bd-highlight">
                    <!-- Button Form -->
                    <button v-if="editRecordID == 0" @click="addRecord" class="btn btn-primary mt-1">
                        Criar registro
                    </button>

                    <!-- Button Form -->
                    <button v-if="editRecordID != 0" @click="updateRecord" class="btn btn-warning mt-1">
                        Editar registro
                    </button>
                </div>
                <div class="p-2 bd-highlight">
                    <button class="btn btn-warning" id="button-addon1" @click="record = cleanrecord">
                        Limpar formulário
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import axios from 'axios';


export default {
    props: ['work'],
    data() {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            file: null,
            definition: '',
            deleteCoverSuccess: '',
            deleteRecordSuccess: false,
            editRecordID: 0,
            emptyresults: true,
            errors: {},
            fields: {},
            loadingISBN: false,
            loadingDOI: false,
            loadingEIDR: false,
            loadingSchema: false,
            loadingZ3950: false,
            loadingSearch: false,
            success: false,
            successUpdate: false,
            successUploadCover: '',
            loaded: true,
            EIDRRecord: null,
            ISBNRecord: null,
            Z3950Records: null,
            SchemaRecord: null,
            crossrefRecord: null,
            OAIPMHRecord: null,
            OAIMetadataFormats: null,
            OAISets: null,
            results: null,
            queryname: null,
            originalfilename: '',
            filename: '',
            file: '',
            renamedFile: '',
            record: {
                about: [{ id: "", name: "" }],
                abridged: "",
                abstract: "",
                actor: [],
                alternateName: "",
                author: [],
                base64Image: '',
                bookEdition: "",
                bookFormat: "",
                byartist: [],
                character: [],
                copyrightYear: "",
                countryOfOrigin: "",
                coverimage: '',
                datePublished: '',
                description: '',
                director: [],
                doi: "",
                duration: "",
                embedUrl: "",
                endDate: "",
                image: "",
                inAlbum: [],
                inLanguage: "",
                isaccessibleforfree: false,
                isbn: [{ id: "", type: "" }],
                isrcCode: "",
                issn: "",
                license: "",
                musicby: [],
                name: '',
                numberOfPages: "",
                oaimetadataformat: "oai_dc",
                oaipmh: "",
                oaiset: "",
                productionCompany: [],
                publisher: [{ id: "", name: "" }],
                recordingOf: "",
                startDate: "",
                subtitleLanguage: [],
                temporalCoverage: "",
                titleEIDR: "",
                thumbnailUrl: "",
                translator: [],
                translationOfWork: "",
                type: "Livro",
                uploadDate: "",
                url: "",
            },
            cleanrecord: {
                about: [{ id: "", name: "" }],
                abridged: "",
                abstract: "",
                actor: [],
                alternateName: "",
                author: [],
                base64Image: '',
                bookEdition: "",
                bookFormat: "",
                byartist: [],
                character: [],
                copyrightYear: "",
                countryOfOrigin: "",
                coverimage: '',
                datePublished: "",
                description: "",
                director: [],
                doi: "",
                duration: "",
                embedUrl: "",
                endDate: "",
                image: "",
                inAlbum: [],
                inLanguage: "",
                isaccessibleforfree: false,
                isbn: [{ id: "", type: "" }],
                isrcCode: "",
                issn: "",
                license: "",
                musicby: [],
                name: "",
                numberOfPages: "",
                oaimetadataformat: "",
                oaipmh: "",
                oaiset: "",
                productionCompany: [],
                publisher: [{ id: "", name: "" }],
                recordingOf: "",
                startDate: "",
                subtitleLanguage: [],
                temporalCoverage: "",
                titleEIDR: "",
                thumbnailUrl: "",
                translator: [],
                translationOfWork: "",
                type: "Livro",
                uploadDate: "",
                url: "",
            },
            authorities: {},
            authoritiesOrganization: {},
            response: {},
        };
    },
    methods: {
        addRecord: function (e) {
            e.preventDefault();
            if (this.loaded) {
                this.loaded = false;
                this.success = false;
                this.errors = {};
                const headers = {
                    "Content-Type": "application/json",
                };
                axios
                    .post(
                        "api/works",
                        JSON.stringify(this.record),
                        { headers }
                    )
                    .then((response) => {
                        this.success = true;
                        this.record = this.cleanrecord;
                        window.scrollTo(0, 0);
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    })
                    .catch((error) => {
                        this.loaded = true;
                        console.log(error);
                        // if (error.response.status === 422) {
                        //     this.errors = error.response.data.errors || {};
                        // }
                        // console.log("Error");
                    });
            }
        },
        updateRecord: function (e) {
            e.preventDefault();
            if (this.loaded) {
                this.loaded = false;
                this.success = false;
                this.errors = {};
                const headers = {
                    "Content-Type": "application/json",
                };
                axios
                    .put(
                        "/api/works/" +
                        this.editRecordID,
                        JSON.stringify(this.record),
                        { headers }
                    )
                    .then((response) => {
                        this.successUpdate = true;
                        window.scrollTo(0, 0);
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    })
                    .catch((error) => {
                        this.loaded = true;
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors || {};
                        }
                        console.log("Error");
                    });
            }
        },
        addField: function (field) {
            if (this.record[field] === null) {
                this.record[field] = [];
            }
            this.record[field].push({ name: "" });
        },
        deleteField: function (field, index) {
            this.record[field].splice(index, 1);
            //if (index === 0) this.addField(field);
        },
        deleteRecord(id) {
            if (confirm("Tem certeza que você quer excluir este registro?")) {
                axios.delete(
                    'api/creative_work/' + id
                )
                    .then(response => {
                        console.log(response);
                        this.deleteRecordSuccess = true;
                        location.reload();
                    }
                    )
                    .catch(error => {
                        console.log(error);
                    })
            }
        },
        addAuthor(name) {
            this.record["author"].push({ id: "", id_lattes13: "", viaf: "", function: "Autor", name: name });
        },
        getEIDR(eidr) {
            this.loadingEIDR = true;
            axios
                .get("api/eidr/" + eidr)
                .then((response) => {
                    (this.EIDRRecord = response.data),
                        (this.record.name = this.EIDRRecord.ResourceName);
                })
                .catch(function (error) {
                    console.log(error);
                    this.errored = true;
                })
                .finally(() => (this.loadingEIDR = false));
        },
        getISBN(isbn) {
            axios
                .get(
                    "https://www.googleapis.com/books/v1/volumes?q=isbn:" + isbn
                )
                .then((response) => {
                    (this.ISBNRecord = response.data),
                        (this.record.name =
                            this.ISBNRecord.items[0].volumeInfo.title),
                        (this.record.abstract =
                            this.ISBNRecord.items[0].volumeInfo.description),
                        (this.record.datePublished =
                            this.ISBNRecord.items[0].volumeInfo.publishedDate),
                        (this.record.copyrightYear =
                            this.ISBNRecord.items[0].volumeInfo.publishedDate),
                        (this.record.numberOfPages =
                            this.ISBNRecord.items[0].volumeInfo.pageCount);
                    Object.values(
                        this.ISBNRecord.items[0].volumeInfo.authors
                    ).forEach((val) => {
                        this.record.author.push({
                            id: "",
                            name: val,
                            function: "",
                        });
                    });
                })
                .catch(function (error) {
                    console.log(error);
                    this.errored = true;
                })
                .finally(() => (this.loadingISBN = false));
        },
        getDOI(doi) {
            axios
                .get("https://api.crossref.org/works/" + doi)
                .then((response) => {
                    (this.crossrefRecord = response),
                        (this.record.name =
                            this.crossrefRecord.data.message.title[0]),
                        (this.record.url =
                            this.crossrefRecord.data.message.URL),
                        (this.record.publisher[0].name =
                            this.crossrefRecord.data.message.publisher),
                        (this.record.copyrightYear =
                            this.crossrefRecord.data.message.created[
                            "date-parts"
                            ][0][0]),
                        (this.record.datePublished =
                            this.crossrefRecord.data.message.issued[
                            "date-parts"
                            ][0][0]),
                        Object.values(
                            this.crossrefRecord.data.message.author
                        ).forEach((val) => {
                            this.record.author.push({
                                id: "",
                                id_lattes13: "",
                                viaf: "",
                                name: val.given + " " + val.family,
                                function: "Autor",
                            });
                        });
                    if (this.crossrefRecord.data.message.ISBN) {
                        this.record.isbn[0].id =
                            this.crossrefRecord.data.message.ISBN[0];
                    }
                })
                .catch(function (error) {
                    console.log(error);
                    this.errored = true;
                })
                .finally(() => (this.loadingDOI = false));
        },
        getRecordData(id) {
            axios
                .get("api/creative_work/editor/" + id)
                .then((response) => {
                    if (response.data.about[0].name == null) {
                        response.data.about = [{ id: "", name: "" }];
                    }
                    this.record = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                    this.errored = true;
                })
                .finally(() => (this.loading = false));
            this.getCover(id);
        },
        getOAIPMH(URLOAIPMH) {
            axios
                .get("api/oai/identify?url=" + URLOAIPMH)
                .then((response) => {
                    (this.OAIPMHRecord = response.data),
                        (this.record.name =
                            this.OAIPMHRecord.Identify.repositoryName);
                })
                .catch(function (error) {
                    console.log(error);
                    this.errored = true;
                })
                .finally(() => (this.loading = false));
        },
        getOAIMetadataFormats(URLOAIPMH) {
            axios
                .get("api/oai/listmetadataformats?url=" + URLOAIPMH)
                .then((response) => {
                    this.OAIMetadataFormats = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                    this.errored = true;
                })
                .finally(() => (this.loading = false));
        },
        getOAISets(URLOAIPMH) {
            axios
                .get("api/oai/listsets?url=" + URLOAIPMH)
                .then((response) => {
                    this.OAISets = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                    this.errored = true;
                })
                .finally(() => (this.loading = false));
        },
        getZ3950(isbn, host, hostname) {
            axios
                .get("api/z3950?isbn=" + isbn + "&host=" + host)
                .then((response) => {
                    if (this.Z3950Records !== null) {
                        Object.values(response.data).forEach((val) => {
                            val["source"] = hostname;
                            this.Z3950Records.push(val);
                        });
                    } else {
                        this.Z3950Records = Array();
                        Object.values(response.data).forEach((val) => {
                            val["source"] = hostname;
                            this.Z3950Records.push(val);
                        });
                    }
                })
                .catch(function (error) {
                    console.log(error);
                    this.errored = true;
                })
                .finally(() => (this.loadingZ3950 = false));
        },
        getSchema(url, type) {
            axios
                .get("api/schema/reader?url=" + url + "&type=" + type)
                .then((response) => {
                    (this.SchemaRecord = response.data),
                        (this.record.name = this.SchemaRecord.name),
                        (this.record.url = this.SchemaRecord.url),
                        (this.record.uploadDate = this.SchemaRecord.uploadDate),
                        (this.record.datePublished =
                            this.SchemaRecord.datePublished),
                        (this.record.image = this.SchemaRecord.image),
                        (this.record.thumbnailUrl = this.SchemaRecord.image),
                        (this.record.duration = this.SchemaRecord.duration),
                        (this.record.description =
                            this.SchemaRecord.description),
                        (this.record.embedUrl = this.SchemaRecord.embedURL),
                        Object.values(this.SchemaRecord.author).forEach(
                            (val) => {
                                this.record.author.push({
                                    id: "",
                                    name: val.name,
                                    function: "Author",
                                });
                            }
                        );
                })
                .catch(function (error) {
                    console.log(error);
                    this.errored = true;
                })
                .finally(() => (this.loadingSchema = false));
        },
        search() {
            this.loadingSearch = true;
            axios
                .get("api/creative_work?search=" + this.queryname)
                .then((response) => {
                    this.results = response.data.data;
                    if (response.data.total === 0) {
                        this.emptyresults = true;
                    } else {
                        this.emptyresults = false;
                    }
                })
                .catch((error) => {
                    console.log(error);
                    this.errored = true;
                })
                .finally(() => (this.loadingSearch = false));
        },
        getCover(id) {
            axios
                .get("api/cover?id=" + id + '&title=' + this.record.name)
                .then((response) => {
                    this.record.coverimage = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                    this.errored = true;
                });
        },
        deleteCover(id) {
            if (confirm("Tem certeza que quer excluir esta capa?")) {
                axios
                    .delete("api/cover/" + id)
                    .then((response) => {
                        this.deleteCoverSucess = "Capa excluída com sucesso";
                    })
                    .catch(function (error) {
                        console.log(error);
                        this.errored = true;
                    })
                    .finally(() => (this.loading = false));
            }
        },
        coverOnFileChange(e) {
            this.originalfilename = "Arquivo selecionado: " + e.target.files[0].name;
            this.filename = "Arquivo selecionado: " + e.target.files[0].name;
            this.file = e.target.files[0];
            this.renamedFile = new File([this.file], this.editRecordID + '.png');
        },
        coverSubmitForm(e) {
            e.preventDefault();
            let currentObj = this;
            const config = {
                headers: {
                    'content-type': 'multipart/form-data',
                }
            }

            // form data
            let formData = new FormData();
            formData.append('file', this.renamedFile);

            // send upload request
            axios.post('api/cover_upload', formData, config)
                .then(function (response) {
                    currentObj.successUploadCover = response.data.success;
                    currentObj.filename = "";
                })
                .catch(function (error) {
                    currentObj.output = error;
                });
            this.getCover(this.editRecordID);
        },
        checkPropEmpty() {
            if (!this.work) {
                this.record = this.cleanrecord;
            } else {
                this.editRecordID = this.work.id;
                delete this.work.created_at;
                delete this.work.updated_at;
                delete this.work.id;
                delete this.work.authors;
                this.record = this.work;
            }
        },
        handleFileChange(event) {
            this.file = event.target.files[0];
            this.convertToBase64();
        },
        convertToBase64() {
            const reader = new FileReader();
            reader.onload = (event) => {
                this.record.base64Image = event.target.result;
            };
            reader.readAsDataURL(this.file);
        },
    },
    mounted() {
        this.checkPropEmpty();
    },
    watch: {
        queryname() {
            this.search();
        },
    }
};
</script>