<template>
    <div>
        <h1>Editor</h1>

        {{ work }}

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
                        <a class="btn btn-info btn-sm" href="https://ui.eidr.org/search" target="_blank">EIDR pode ser
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
                    <div class="input-group-prepend">
                        <span class="input-group-text">Autor / Função</span>
                    </div>
                    <input v-model.trim="author.id" autocomplete="off" type="text" aria-label="Author ID"
                        class="form-control" placeholder="ID do autor" id="id" readonly />
                    <input v-model.trim="author.type" autocomplete="off" type="text" aria-label="Author Type"
                        class="form-control" placeholder="Tipo" id="type" readonly />
                    <input v-model.trim="author.viaf" autocomplete="off" type="text" aria-label="VIAF" class="form-control"
                        placeholder="VIAF" id="viaf" readonly />
                    <!-- <input
                        v-model="author.name"
                        autocomplete="off"
                        type="text"
                        class="form-control"
                        placeholder="Digite o nome"
                    /> -->
                    <input class="form-control" list="datalistAuthority" placeholder="Digite o nome" v-model="author.name"
                        @input="
                            getIDAuthority(author), getAuthorities(author.name)
                            " />
                    <datalist id="datalistAuthority">
                        <option v-for="authority in authorities" :value="authority.name" :key="authority.id"
                            :id="authority.id"></option>
                    </datalist>
                    <select v-model="author.function" autocomplete="off" class="form-select" aria-label="Author funcion"
                        placeholder="Função">
                        <option value="Author" selected>Autor</option>
                        <option value="Organizer" selected>Organizador</option>
                        <option value="Writer of preface" selected>
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
                bookEdition: "",
                bookFormat: "",
                byartist: [],
                character: [],
                copyrightYear: "",
                countryOfOrigin: [{ code: "BR" }],
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
                inlanguage: [{ code: "pt-BR" }],
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
                bookEdition: "",
                bookFormat: "",
                byartist: [],
                character: [],
                copyrightYear: "",
                countryOfOrigin: [{ code: "BR" }],
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
                inlanguage: [{ code: "pt-BR" }],
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
        addField: function (field) {
            if (this.record[field] === null) {
                this.record[field] = [];
            }
            this.record[field].push({ id: "", name: "" });
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
            this.record["author"].push({ id: "", name: name });
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
                                name: val.given + " " + val.family,
                                function: "Author",
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
        getAuthorities(autQuery) {
            axios
                .get("api/things/?search=" + autQuery)
                .then((response) => {
                    this.authorities = response.data.data;
                })
                .catch(function (error) {
                    console.log(error);
                    this.errored = true;
                })
                .finally(() => (this.loading = false));
        },
        getAuthoritiesOrganization(autOrgQuery) {
            axios
                .get("api/things/?type=organization&search=" + autOrgQuery)
                .then((response) => {
                    this.authoritiesOrganization = response.data.data;
                })
                .catch(function (error) {
                    console.log(error);
                    this.errored = true;
                })
                .finally(() => (this.loading = false));
        },

        getIDAuthority(author) {
            if (author.name == "") {
                return (author.id = "");
            }
            axios.get("api/things/?search=" + author.name).then((response) => {
                if (response.data.data.length > 0) {
                    author.id = response.data.data[0].id.toString();
                    author.type = response.data.data[0].type;
                    author.viaf = response.data.data[0].viaf;
                } else {
                    author.id = "";
                    author.type = "";
                    author.viaf = "";
                }
            });
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
                        "api/creative_work/" +
                        this.record.type +
                        "/" +
                        this.editRecordID,
                        JSON.stringify(this.record),
                        { headers }
                    )
                    .then((response) => {
                        this.success = true;
                        location.reload();
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
        checkPropEmpty() {
            if (!this.work) {
                this.record = this.cleanrecord;
            } else {
                this.record.name = this.work.name;
                this.record.description = this.work.description;
                this.record.datePublished = this.work.datePublished;
            }
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