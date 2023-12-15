<template>
    <div class="parent d-flex justify-content-center">
        <b-avatar size="5rem" src="https://cdn.dribbble.com/users/2159096/screenshots/4841370/speed-06.png" class=" child" ></b-avatar>
        <svg width="200" height="90" class="child">
            <circle cx="100" cy="40" :r="radius" id="circle_loader" :stroke-dashoffset="dashoffset"
                    :stroke-dasharray="dasharray"></circle>
        </svg>

    </div>
</template>

<script>
import event from "../../event";

export default {
    props: ['img', 'data', 'd_offset', 'd_array', 'state', 'col'],
    name: "ProgressBar",
    data() {
        return {
            dashoffset: '78.5',
            dasharray: '100, 314',
            radius: 38,
            count: 0, // count of complate task
            part: 0 //like 0.05 => one part of 20 part || 0.1 => one part of 10 part
        }
    },
    created() {
        // "1" becuse we dont have 100 and we shold work with number between 0 1 like 0.1 0.05 and something like this
        // this.part = 1 / this.task_count; // 1 / count of all task == part of circle
        this.part = 1 / this.data.length;
        this.check(); // check without this function and see rsult // soooo important!!!!!!

        for (let i in this.data){
            if(this.data[i][this.col] == this.state) {
                this.plus();
            }
        }

        event.$on('moveProgress',()=>{
            console.log("im checking!")
            this.plus();
        } )

    },
    updated() {
        console.log("update")

    },
    methods: {
        plus() {
            if (this.count < 1) {
                this.count += this.part;
                this.check();
            } else {
                console.log("complate");
            }
        },
        check() {
            this.dasharray = this.count * 2 * Number(this.radius * Math.PI) + ", " + ((1 - this.count) * 2 * Number(this.radius * Math.PI));
            this.dashoffset = Number(this.radius) * Math.PI * 0.5;
        }
    }
}
</script>

<style scoped>

div.parent {
    position: relative;
}

.child {
    position: absolute;
}

/*.svg_circle_loader {*/
/*    border-radius: 50%;*/
/*    border: none;*/
/*    position: absolute;*/
/*    !*top: -45px;*!*/
/*    !*right: -45px;*!*/
/*    !*right: 10px;*!*/
/*    !*margin: none;*!*/
/*    !*padding: none;*!*/

/*}*/

#circle_loader {
    fill: none;
    stroke: #24ab00;
    stroke-width: 3px;
    /*stroke-dashoffset: 78.5;*/
    /*stroke-dasharray: 0, 314;*/
    transition: all 0.2s linear;
}
</style>
