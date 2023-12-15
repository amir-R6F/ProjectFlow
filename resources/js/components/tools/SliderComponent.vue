<template>
    <div class="grid grid-rows-1 grid-flow-col h-50">
        <div
            class="bg-white w-16 h-16 mr-16 border-r border-gray-400 flex items-center justify-center text-black cursor-pointer"
            @click="previousSlide()">&#x276E;
        </div>
        <ul class="relative w-48 h-72">
            <li class="slider bg-red-500 active slide first">Hello</li>
            <li class="slider bg-blue-500 after slide">There</li>
            <li class="slider bg-blue-400 slide">There</li>
            <li class="slider bg-blue-300 slide">There</li>
            <li class="slider bg-blue-200 slide">There</li>
            <li class="slider bg-blue-100 slide">There</li>
            <li class="slider bg-yellow-500 before slide last">Booya!</li>
        </ul>
        <div class="bg-white w-16 h-16 flex items-center justify-center text-black cursor-pointer" @click="nextSlide()">
            &#x276F;
        </div>
    </div>
</template>

<script>
export default {
    name: "SliderComponent",
    data() {
        return {}
    },
    methods: {
        nextSlide() {
            let activeSlide = document.querySelector('.active');
            activeSlide.classList.remove('active');
            activeSlide.classList.add('before');

            if (activeSlide.classList.contains("first")) {
                let last = document.querySelector('.last');
                last.classList.remove('before');
            } else {
                activeSlide.previousElementSibling.classList.remove('before');
            }

            let nextSlide = activeSlide.nextElementSibling;
            if (nextSlide) {
                nextSlide.classList.remove('after');
                nextSlide.classList.add('active');
                if (nextSlide.nextElementSibling){
                    nextSlide.nextElementSibling.classList.add('after');
                }else{
                    let first = document.querySelector('.first');
                    first.classList.add('after');
                }
            } else {
                let first = document.querySelector('.first');
                first.classList.remove('after');
                first.classList.add('active');
                first.nextElementSibling.classList.add('after')
            }
        },
        previousSlide() {
            let activeSlide = document.querySelector('.active');
            activeSlide.classList.remove('active');
            activeSlide.classList.add('after');

            if (activeSlide.classList.contains("last")) {
                let first = document.querySelector('.first');
                first.classList.remove('after');
            } else {
                activeSlide.nextElementSibling.classList.remove('after');
            }

            let previousSlide = activeSlide.previousElementSibling;
            if (previousSlide) {
                previousSlide.classList.remove('before');
                previousSlide.classList.add('active');
                if (previousSlide.previousElementSibling){
                    previousSlide.previousElementSibling.classList.add('before');
                }else{
                    let last = document.querySelector('.last');
                    last.classList.add('before');
                }
            } else {
                let last = document.querySelector('.last');
                last.classList.remove('before');
                last.classList.add('active');
                last.previousElementSibling.classList.add('before');
            }
        }
    }
}
</script>

<style scoped>

</style>
