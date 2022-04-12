<template>
    <div>
        <div v-for="(item, key) in rows" :class="getColor(item)" style="font-size: 9.6px; font-weight: 700">
             &bull;
            {{ item.title }}
            {{ item.labels[!item.value ? 1 : 0] }}
             
            <!-- <span class="badge d-block" :class="getColor()">
                {{title}} 
                {{ key == 0 ? "first" : key == rows.length - 1 ? "last" : "mid" }}
                {{ labels[!value ? 1 : 0] }}        
            </span> !-->
        </div>
    </div>
</template>

<script>
export default {
    props: ["rows"],

    methods: {
        getColor(row) {
            if (!row.color) {
                return !row.value ? "badge-danger" : "badge-success";
            }

            return "badge-" + row.color;
        }
    }
};
</script>
