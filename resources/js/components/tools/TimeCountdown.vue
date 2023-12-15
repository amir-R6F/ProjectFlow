<template>
    <div>
        <b-badge class="my-1" size="sm" :variant="CTimer" v-if="!state"
                 v-text="days+'d '+hours+'h '+minutes+'m '+secends+'s'"></b-badge>
        <b-badge class="my-1" size="sm" variant="danger" v-else>Expire</b-badge>
    </div>
</template>

<script>
import event from "../../event";

export default {
    name: "TimeCountDown",
    props: ['data'],
    data() {
        return {
            form: this.data,
            CTimer: "success",
            days: null,
            hours: null,
            minutes: null,
            secends: null,
            state: false

        }
    },
    methods: {
        time() {
            let countDownDate = new Date(this.form.dueDate);
            let T = this;
            let x = setInterval(function () {

                let now = new Date();

                let distance = countDownDate - now;

                T.days = Math.floor(distance / (1000 * 60 * 60 * 24));
                T.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                T.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                T.secends = Math.floor((distance % (1000 * 60)) / 1000);
                // console.log(countDownDate * 0.5 < distance)
                // if ((countDownDate * 0.5) < distance && distance > 0 ) {
                //     T.CTimer = "warning";
                // } else
                    if (distance < 0) {
                    clearInterval(x);
                    T.state = true;
                    // T.expire();
                    return "EXPIRED"
                }
            }, 1000);
        },
        expire() {
            let condition = new Date(this.form.dueDate) - new Date();
            if ((this.form.state != 4) && (this.form.state != 6)) {
                if (condition <= 0) {
                    (async () => {
                        let result = await this.$helper.update('updateTodo', this.form);
                        event.$emit('expire', result);
                    })();
                }
            }
        },
    },
    created() {
        this.time();
    },
}
</script>

<style scoped>

</style>
