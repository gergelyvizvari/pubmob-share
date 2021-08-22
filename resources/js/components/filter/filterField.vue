<template>
    <v-col cols="4" sm="3" md="2" class="pt-0 pb-0">
        <v-select
            dense
            name="field"
            :items="filterOptions"
            :value="value"
            @input="$emit('input', $event)"
        ></v-select>
    </v-col>
</template>

<script>
export default {
    props: ["value", "filterOptions", "field"]
};
</script>

<style scoped></style>
