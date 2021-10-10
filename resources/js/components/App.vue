<template>
    <div class="w-100 mb-4 px-4 py-3 text-white" style="border-radius: 0;">
        <h2>Zarządzanie listą</h2>
        <div class="d-flex align-content-start">
            <span class="all badge rounded-pill">wszystko</span>
            <span class="alphaSort badge rounded-pill" v-on:click="alphaClick()">{{ sortowanie }}</span>
            <span :id-rodzic="-24" class="plus badge rounded-pill"><i class="fas fa-plus"></i></span>
        </div>
    </div>
    <div class="list-group mb-5">
        <kategoria
            v-for="kategoria in szukajBezRodzica" :key="kategoria.id"
            :kategorie="kategorie"
            :kategoria="kategoria"
        />
    </div>
</template>

<script>
    import Kategoria from './Kategoria.vue';

    export default {
        props: {
            props_kategorie: [Array, String]
        },
        data() {
            return {
                kategorie: JSON.parse(this.props_kategorie),
                sortowanie: 'none'
            }
        },
        methods: {
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
            szukajBezRodzica() {
                var elo = [];
                for(var i=0; i<this.kategorie.length; i++)
                {
                    if(!this.kategorie[i].dziecko)
                    {
                       elo.push(this.kategorie[i]);
                    }
                }

                switch(this.sortowanie)
                {
                    case this.sortowanie = 'none':
                        break;
                    case this.sortowanie = 'a-z':
                        elo.sort(function(a, b){
                            if(a.kategoria < b.kategoria) { return -1; }
                            if(a.kategoria > b.kategoria) { return 1; }
                            return 0;
                        });
                        break;
                    case this.sortowanie = 'z-a':
                        elo.sort(function(a, b){
                            if(a.kategoria > b.kategoria) { return -1; }
                            if(a.kategoria < b.kategoria) { return 1; }
                            return 0;
                        });
                        break;
                }

                return elo;
            }
        },
        components: {
            'kategoria': Kategoria
        }
    }
</script>
