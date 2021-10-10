<template>
    <div v-if="kategoria.rodzic.length == 0" class="list-group-item d-flex justify-content-between align-items-center">
        <span class="w-100"> {{ kategoria.kategoria }} </span>
        <span :id-moje="kategoria.id" :czy-rodzic="false" class="zmienRodzica badge rounded-pill"><i class="fas fa-arrows-alt-v"></i></span>
        <span :id-moje="kategoria.id" :czy-rodzic="false" class="usunKategorie badge rounded-pill"><i class="fas fa-trash-alt"></i></span>
        <span :id-rodzic="kategoria.id" class="zmienNazwe badge rounded-pill"><i class="fas fa-pen"></i></span>
        <span :id-rodzic="kategoria.id" class="plus badge rounded-pill"><i class="fas fa-plus"></i></span>
    </div>
    <div v-else>
        <div class="list-group-item d-flex justify-content-between align-items-center">
            <span class="more w-100" :id-toggle="'#item-'+kategoria.id"><i class="fas fa-chevron-right"></i> {{ kategoria.kategoria }} </span>
            <span class="alphaSort badge rounded-pill" v-on:click="alphaClick()">{{ sortowanie }}</span>
            <span :id-moje="kategoria.id" :czy-rodzic="true" class="zmienRodzica badge rounded-pill"><i class="fas fa-arrows-alt-v"></i></span>
            <span :id-moje="kategoria.id" :czy-rodzic="true" class="usunKategorie badge rounded-pill"><i class="fas fa-trash-alt"></i></span>
            <span :id-rodzic="kategoria.id" class="zmienNazwe badge rounded-pill"><i class="fas fa-pen"></i></span>
            <span :id-rodzic="kategoria.id" class="plus badge rounded-pill"><i class="fas fa-plus"></i></span>
        </div>
        <div class="list-group collapse" :id="'item-'+kategoria.id">
            <kategoria
            v-for="relacja in sortujDzieci" :key="relacja.id"
                :kategorie="kategorie"
                :kategoria="relacja"
            />
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            kategorie: [Array, Object],
            kategoria: [Array, Object],
        },
        data() {
            return {
                sortowanie: 'none',
            }
        },
        methods: {
            getKategoriaById(id) {
                return this.kategorie.find(element => element.id == id)
            },
            napisz(tekst) {
                alert(tekst);
            },
            alphaClick()
            {
                switch(this.sortowanie)
                {
                    case this.sortowanie = 'none':
                        this.sortowanie = 'a-z';
                        break;
                    case this.sortowanie = 'a-z':
                        this.sortowanie = 'z-a';
                        break;
                    case this.sortowanie = 'z-a':
                        this.sortowanie = 'none';
                        break;
                }
            }
        },
        computed: {
            sortujDzieci() {
                var dzieci = [];

                for(var i=0; i<this.kategoria.rodzic.length; i++)
                {
                    dzieci.push(this.getKategoriaById(this.kategoria.rodzic[i].dziecko));
                }

                switch(this.sortowanie)
                {
                    case this.sortowanie = 'none':
                        break;
                    case this.sortowanie = 'a-z':
                        dzieci.sort((a, b) => a.kategoria.localeCompare(b.kategoria));
                        break;
                    case this.sortowanie = 'z-a':
                        dzieci.sort((a, b) => b.kategoria.localeCompare(a.kategoria));
                        break;
                }

                return dzieci;
            }
        }
    }
</script>
