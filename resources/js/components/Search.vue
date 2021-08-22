<template>
    <v-card>
        <v-container>
            <div
                v-for="(filter, filterIndex) in filters"
                :key="filter.id"
                @keyup.enter="loadData()"
            >
                <v-row style="margin-bottom: -8px;">
                    <filter-selector
                        :filterIndex="filterIndex"
                        :operators="operators"
                        :value="filter.operator"
                        v-model="filter.operator"
                    />
                    <filter-field
                        :filterOptions="filterOptions"
                        :value="filter.field"
                        v-model="filter.field"
                    />
                    <filter-value v-model="filter.value" />
                    <filter-action-buttons
                        :addFilter="() => addFilter(filterIndex)"
                        :removeFilter="() => removeFilter(filterIndex)"
                        :filtersLength="filters.length"
                    />
                </v-row>
            </div>
        </v-container>

        <v-col>
            <v-btn small @click="loadData()">Keresés</v-btn>
        </v-col>
    </v-card>
</template>

<script>
import filterActionButtons from "./filter/filterActionButtons.vue";
import FilterField from "./filter/filterField.vue";
import FilterSelector from "./filter/filterSelector.vue";
import FilterValue from "./filter/filterValue.vue";

export default {
    components: {
        filterActionButtons,
        FilterValue,
        FilterField,
        FilterSelector
    },
    name: "Search",
    computed: {
        filters() {
            return this.$store.state.filters;
        },
        filterOptions() {
            return this.$store.state.filterOptions;
        },
        operators() {
            return this.$store.state.filterOperators;
        }
    },
    methods: {
        setOperator(ind) {
            console.log(this.filters[ind]);
        },
        addFilter(ind) {
            let tmpFilters = [...this.$store.state.filters];
            tmpFilters.splice(ind + 1, 0, {
                field: "",
                name: "field-name",
                id: this.$store.state.lastFilterId + 1,
                operator: "and"
            });
            this.$store.state.filters = tmpFilters;
            this.$store.state.lastFilterId++;
        },
        removeFilter(ind) {
            let tmpFilters = [...this.$store.state.filters];
            tmpFilters.splice(ind, 1);
            this.$store.state.filters = tmpFilters;
        },
        updateField(ind, value) {
            let tmpFilters = [...this.$store.state.filters];
            tmpFilters[ind].value = value;
            this.$store.state.filters = tmpFilters;
        },
        loadData() {
            this.$store.commit("fetchData");
        }
    }
};
</script>
