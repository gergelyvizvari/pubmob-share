<template>
    <v-row>
        <v-col cols="12">
            <div>{{ this.$store.state.message }}</div>
            <v-data-table
                dense
                show-expand
                class="elevation-1"
                single-expand
                item-key="id_str"
                :items-per-page="10"
                :headers="headers"
                :items="this.$store.state.data"
                :loading="this.$store.state.loading"
                :footer-props="{ 'items-per-page-options': [10, 15, 30, 100] }"
            >
                <template v-slot:no-data>
                    Nincs a keresésnek megfelelõ adat
                </template>
                <template v-slot:loading-text>Keresés...</template>
                <template v-slot:loading>Keresés...</template>
                <template v-slot:expanded-item="{ headers, item }">
                    <td
                        :colspan="headers.length"
                        class="pt-3"
                        style="overflow: auto;max-width: calc(100vw - 35px);"
                    >
                        <p v-if="item.container_title">
                            <b> Folyóirat: </b> {{ item.container_title }}
                        </p>
                        <p v-if="item.issn"><b> ISSN: </b> {{ item.issn }}</p>
                        <p v-if="item.issued">
                            <b> Megjelenés éve: </b> {{ item.issued }}
                        </p>
                        <p v-if="item.volume">
                            <b> Évfolyam: </b> {{ item.volume }}
                        </p>
                        <p v-if="item.issue">
                            <b> Füzetszám: </b> {{ item.issue }}
                        </p>
                        <p v-if="item.page">
                            <b> Oldalszám: </b> {{ item.page }}
                        </p>
                        <p v-if="item.title"><b> Cím: </b> {{ item.title }}</p>
                        <p v-if="item.authors">
                            <b> Szerzō(k): </b> {{ item.authors }}
                        </p>
                        <p v-if="item.doi">
                            <b> DOI: </b>
                            <a
                                target="_blank"
                                :href="`https://doi.org/${item.doi}`"
                            >
                                {{ item.doi }}
                            </a>
                        </p>
                        <p v-if="item.url">
                            <b> URL: </b>
                            <a target="_blank" :href="item.url">link</a>
                        </p>
                        <p v-if="item.note">
                            <b> Megjegyzés: </b> {{ item.note }}
                        </p>
                        <p v-if="item.language">
                            <b> Nyelv: </b> {{ item.language }}
                        </p>
                        <p v-if="item.source">
                            <b> Adatforrás: </b> {{ item.source }}
                        </p>
                        <p v-if="item.abstract">
                            <b> Absztrakt: </b> {{ item.abstract }}
                        </p>
                        <p>
                            <b> Hivatkozás: </b> {{ limitFor3(item.authors) }}
                            {{ item.title }}. {{ item.container_title }}.
                            {{ item.issued }}; {{ item.volume }}({{
                                item.issue
                            }}):{{ item.page }}
                        </p>
                    </td>
                </template>
                <template v-slot:[`item.title`]="{ item }">
                    {{ limitText(item.title, 50) }}
                </template>
                <template v-slot:[`item.authors`]="{ item }">
                    {{ getSortAuthors(item.authors) }}
                </template>
                <template v-slot:[`item.source`]="{ item }">
                    {{ item.source }}
                </template>
                <template v-slot:[`item.journal_abbreviation`]="{ item }">
                    {{ item.journal_abbreviation }}
                </template>
                <template v-slot:[`item.issued`]="{ item }">
                    {{ item.issued }}
                </template>
                <template v-slot:[`item.actions`]="{ item }">
                    <dialog-delete
                        :title="item.title"
                        :onDelete="() => deleteItem(item)"
                    />
                </template>
            </v-data-table>
        </v-col>
        <v-col v-if="this.$store.state.data.length > 0">
            <v-btn small @click="exportCsv()">Exportálás csv fájlba</v-btn>
        </v-col>
    </v-row>
</template>

<script>
import Axios from "axios";
import DialogDelete from "./dialogDelete.vue";
export default {
    components: { DialogDelete },
    data() {
        const headers = [
            { text: "Cím", value: "title" },
            { text: "Szerzõ(k)", value: "authors" },
            { text: "Folyóirat", value: "container_title" },
            { text: "Megjelenés", value: "issued" },
            { text: "Oldalszám", value: "page" }
        ];

        if (window.auth_user) {
            headers.push({ text: "", value: "actions" });
        }

        const csrf = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");

        return { headers, csrf };
    },
    computed: {},
    methods: {
        exportCsv() {
            this.$store.commit("getCsv");
        },
        getId(id) {
            console.log(id);
            let parts = id.split("/items/");
            let right = parts[1];
            let left = parts[0].split("/").pop();
            return left + " / " + right;
        },
        limitText(text, length, direction = 1) {
            if (text == undefined) return "";
            if (text.length > length + 3) {
                if (direction == 1) {
                    return text.substring(0, length) + "...";
                }
                if (direction == -1) {
                    return (
                        "..." +
                        text.substring(text.length, text.length - length)
                    );
                }
            }
            return text;
        },
        getSortAuthors(authors) {
            let all = authors.split(",");

            if (all.length > 1) {
                return all[0] + ", et.al";
            }

            return all[0];
        },
        deleteItem(item) {
            Axios.post("api/delete-item", { id_str: item.id_str }).then(() => {
                this.$store.state.data = this.$store.state.data.filter(curr => {
                    return item.id_str != curr.id_str;
                });
            });
        },
        limitFor3(authors) {
            let all = authors.split(",");

            if (all.length > 3) {
                return all[0] + ", " + all[1] + ", " + all[2] + ", et.al:";
            }

            if (authors != "") {
                authors += ":";
            }

            return authors;
        }
    }
};
</script>
