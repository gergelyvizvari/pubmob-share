/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
// eslint-disable-next-line no-undef
require('./bootstrap');
// eslint-disable-next-line no-undef
window.Vue = require('vue');

import Papa from "papaparse";
import Vuetify from 'vuetify';
import Home from './views/Home.vue'
import Vuex from 'vuex';
import Axios from 'axios';

const store = new Vuex.Store({
    state: {
        loading: false,
        message: '',
        data: [],
        filterOperators: [
            { text: "és", value: "and" },
            { text: "vagy", value: "or" },
            { text: "nem", value: "not" }
        ],
        filters: [
            {
                field: "title",
                id: 0,
                operator: "and",
                value: ""
            }
        ],
        filterOptions: [
            {
                text: "Cím",
                value: "title"
            },
            {
                text: "Szerzõ(k)",
                value: "authors"
            },
            {
                text: "Folyóirat",
                value: "container_title"
            },
            {
                text: "Év",
                value: "issued"
            },
            {
                text: "Füzetszám",
                value: "issue"
            },
            {
                text: "Nyelv",
                value: "language"
            },
            {
                text: "ISSN",
                value: "ISSN"
            },
            {
                text: "DOI",
                value: "doi"
            }
        ],
        lastFilterId: 0
    },
    mutations: {
        fetchData(state) {
            state.loading = true;
            Axios.post('api/get-data', { filters: state.filters }).then((response) => {
                let data = response.data.data.map((record) => {
                    let authors = JSON.parse(record.authors);
                    if (authors == "[]") {
                        record.authors = '';
                        return record;
                    }

                    let names = authors.map(author => {
                        let item = (author.family ? author.family : "") + " " + (author.given ? author.given : "") + (author.name ? author.name : "");
                        item = item.trim();
                        return item;
                    });

                    record.authors = names.join(", ");
                    return record;
                });
                state.data = data;
                state.loading = false;
            });
        },
        getCsv(state) {
            state.loading = true;

            let filename = 'export.csv';
            let columns = null;

            let csv = Papa.unparse({
                data: state.data, fields: columns,
            }, {
                quotes: true, //or array of booleans
                delimiter: ","
            });

            if (csv == null) return;

            var blob = new Blob([csv]);
            if (window.navigator.msSaveOrOpenBlob) {
                // IE hack; see http://msdn.microsoft.com/en-us/library/ie/hh779016.aspx
                window.navigator.msSaveBlob(blob, filename);
            } else {
                var a = window.document.createElement("a");
                a.href = window.URL.createObjectURL(blob, { type: "text/csv" });
                a.download = filename;
                document.body.appendChild(a);
                a.click();  // IE: "Access is denied"; see: https://connect.microsoft.com/IE/feedback/details/797361/ie-10-treats-blob-url-as-cross-origin-and-denies-access
                document.body.removeChild(a);
            }
            state.loading = false;
        },
        specifyFiletrs(state, message) {
            state.loading = false;
            state.data = [];
            state.message = message;
        }
    }
})

// eslint-disable-next-line no-undef
Vue.use(Vuetify);

// eslint-disable-next-line no-undef
new Vue({
    el: '#vue-app',
    vuetify: new Vuetify(),
    store,
    render: h => h(Home)
});

