<template>
    <div>
        <h3 class="mt-3 mb-3">Consultar tabela Cutter Sanborn</h3>
    </div>
    <div class="form-floating mb-3">
        <input type="text" class="form-control" id="floatingInput"
            placeholder="Digite um busca no formato:  Sobrenome, Nome" v-model="searchTerm" @input="fetchData">
        <label for="floatingInput">Digite um busca no formato: Sobrenome, Nome</label>
    </div>
    <div class="alert alert-info" role="alert" v-if="results.codigo">
        Resultado: {{ results.codigo }}
    </div>
</template>
<script>
import axios from 'axios';


export default {
    data() {
        return {
            searchTerm: '',
            results: []
        }
    },
    methods: {
        fetchData() {
            axios.get('/api/cutter', {
                params: {
                    search: this.searchTerm.toLowerCase().replaceAll(',', '')
                }
            }).then(response => {
                this.results = response.data;
            });
        }
    }
}
</script>