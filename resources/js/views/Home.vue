<template>
    <v-app id="inspire">
        <AppBar />
        <v-content>
            <v-container fluid class="mt-10">
                <search />
                <search-result />
            </v-container>
        </v-content>
    </v-app>
</template>

<script>
import Search from "../components/Search.vue";
import SearchResult from "../components/SearchResult.vue";
import AppBar from "../components/AppBar.vue";

export default {
    name: "Home",
    components: {
        Search,
        SearchResult,
        AppBar
    },
    mounted() {}
};
</script>
