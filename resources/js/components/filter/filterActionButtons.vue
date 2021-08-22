<template>
    <v-col cols="2" sm="2" md="2" class="pt-2 pb-2 ma-0 pa-0">
        <v-btn small icon color="pink" class="pt-0 pb-0" @click="addFilter">
            <v-icon>mdi-plus</v-icon>
        </v-btn>
        <v-btn
            v-if="filtersLength > 1"
            small
            icon
            color="pink"
            class="pt-0 pb-0"
            @click="removeFilter"
        >
            <v-icon>mdi-minus</v-icon>
        </v-btn>
    </v-col>
</template>

<script>
export default {
    props: ["filtersLength", "removeFilter", "addFilter"]
};
</script>

<style scoped></style>
