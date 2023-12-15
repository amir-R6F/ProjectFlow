<template>
    <div>
        <!--        <b-modal ref="createChild" hide-footer size="sm">-->
        <!--            <template #modal-header>-->
        <!--                <div class="w-full bg-sky p-1 rounded-md">-->
        <!--                    <h1 class="text-lg text-white">-->
        <!--                        Add Child to Task-->
        <!--                    </h1>-->
        <!--                </div>-->
        <!--            </template>-->
        <!--            <insert-task :t_id="task.id" :p_id="task.project_id"></insert-task>-->
        <!--        </b-modal>-->

        <div class="card-box"
             @click="openTask"
             :id="task.id"
             @contextmenu="showContext(task, task.id, $event)">

            <div class="flex flex-row justify-between">
                <span>
                    {{ $helper.cutLength(task.title, 20) }}
                </span>

                <b-icon icon="three-dots" class="cursor-pointer"></b-icon>
            </div>
            <div class="grid grid-rows-1 grid-flow-col justify-items-stretch">
                <div class="justify-self-end">
                    <b-icon icon="check" variant="success"></b-icon>
                    <b-icon icon="flag" variant="danger"></b-icon>
<!--                    <b-icon icon="diagram2-fill" v-if="task.sub_task.length>0"></b-icon>-->
                </div>
            </div>
        </div>


    </div>
</template>

<script>
import event from "../event";
import DocumentManager from "./tools/DocumentManager";

export default {
    components: {DocumentManager},
    props: ['p_task', 'addChild', 'delChild'],
    name: "TaskProject",
    data() {
        return {
            time: null,
            task: this.p_task,
            info: [],
            subInfo: [],
            value: [],

        }
    },
    created() {
        event.$on("myDelete", res => {
            this.deleteTask(res);
        });
    },
    methods: {
        showContext(obj, id, ev) {
            event.$emit("showContext", {data: obj, id: id, e: ev})
        },
        openTask() {
            // this.$refs["taskManager"].show();
            event.$emit("openTaskManager", this.task.id);
        },
        // createNewChild(obj) {
        //     this.task.sub_task = this.task.sub_task.filter((data, i) => {
        //         return data.id != obj.id;
        //         // return data.id == obj.id? obj.state = 6: obj;
        //     })
        //     this.task.sub_task.push(obj);
        // },
        // deleteChild(obj) {
        //     this.task.sub_task = this.task.sub_task.filter((data) => {
        //         return data.id != obj.id;
        //     })
        // },


        // showInfo(data) {
        //     this.info = [];
        //     this.subInfo = [];
        //
        //     for (let i in data.sub_task)
        //         this.$helper.information_modal(this.subInfo, data.sub_task[i], ["id", "sub_task", "state", "parents", "created_at", "project_id", "parent_id", "updated_at"]);
        //
        //     this.$helper.information_modal(this.info, data, ["id", "sub_task", "parents", "state", "project_id", "parent_id", "created_at", "updated_at"]);
        //     this.$refs['info'].show();
        // },
        deleteTask(data) {
            axios.post("deleteTask", data).then(res => {
                this.delChild(res.data.data)
            }).catch(e => {
                console.log(e)
            })

        },
    },
    updated() {

    }

}
</script>

<style scoped>
</style>
<!--form: {-->
<!--task: null,-->
<!--subject: null,-->
<!--dueDate: null,-->
<!--dependent: false,-->
<!--state: 1,-->
<!--project_id: 1,-->
<!--parent_id: this.p_task.id,-->
<!--user_list: [1, 2, 3]-->
<!--},-->


<!--<b-row class="grid grid-cols-12 justify-items-stretch">-->

<!--<b-col cols="2">-->
<!--    <span>{{ $helper.cutLength(task.title, 20) }}</span>-->
<!--</b-col>-->


<!--<b-col cols="5">-->
<!--    &lt;!&ndash;                <b-progress class="mt-2" max="100" show-value variant="success" :value="checker()"></b-progress>&ndash;&gt;-->
<!--</b-col>-->

<!--<b-col cols="4" class="grid grid-cols-4 justify-items-stretch">-->

<!--    &lt;!&ndash;                <b-button variant="none" size="sm">&ndash;&gt;-->
<!--    &lt;!&ndash;                    <b-icon icon="info-circle-fill"  @click="showInfo(task)"></b-icon>&ndash;&gt;-->
<!--    &lt;!&ndash;                </b-button>&ndash;&gt;-->

<!--    <b-button class="sm" variant="none" @click="deleteTask(task)">-->
<!--        <b-icon icon="trash-fill"></b-icon>-->
<!--    </b-button>-->

<!--    &lt;!&ndash;                <b-button class="sm" variant="none" @click="$refs['document'].show()">&ndash;&gt;-->
<!--    &lt;!&ndash;                    <b-icon icon="cloud-upload-fill" ></b-icon>&ndash;&gt;-->
<!--    &lt;!&ndash;                </b-button>&ndash;&gt;-->

<!--    <b-button class="sm" variant="none" v-if="task.dependent == false && task.state != 6 ">-->
<!--        <b-icon icon="check-circle-fill" scale="0.90"-->
<!--                @click="updateState(task.id)"></b-icon>-->
<!--    </b-button>-->

<!--    <b-button class="sm" variant="none" v-if="task.dependent">-->
<!--        <b-icon icon="link" scale="0.90"></b-icon>-->
<!--    </b-button>-->

<!--    <b-button variant="none" size="sm" class="text-gray-900" v-if="task.state == 6">-->
<!--        <b-icon icon="award-fill"></b-icon>-->
<!--    </b-button>-->

<!--    <b-button variant="none" size="sm" class="text-gray-900" v-else>-->
<!--        <time-countdown :data="task"></time-countdown>-->
<!--    </b-button>-->

<!--    <b-button variant="none" size="sm" class="justify-self-end" @click="openTask">-->
<!--        <b-icon icon="box-arrow-in-up-right"></b-icon>-->
<!--    </b-button>-->


<!--</b-col>-->
<!--</b-row>-->
