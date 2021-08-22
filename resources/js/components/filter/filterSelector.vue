<template>
    <v-col cols="12" sm="3" md="2" class="pt-0 pb-0">
        <label v-if="filterIndex == 0" class="d-block pt-2">
            Szűrő
        </label>
        <v-select
            dense
            v-if="filterIndex > 0"
            :items="operators"
            :value="value"
            @input="$emit('input', $event)"
            style="max-width:100px"
        ></v-select>
    </v-col>
</template>

<script>
export default {
    props: ["filterIndex", "value", "operators"]
};
</script>

<style scoped></style>
