<template>
    <div id="context-menu" ref="contextmenu" v-show="visibility" v-click_away="reset">
        <div v-for="(option, i) in options" :key="i" class="item" @click="option.func(); hideContextMenu()">
            <b-icon :icon="option.icon" scale="1.25" :variant="option.color? option.color: 'dark'"></b-icon>
            <span v-text="option.title" class="mx-2"></span>
        </div>
    </div>
</template>

<script>
import event from "../../event";

export default {
    name: "ContextMenu",
    data() {
        return {
            visibility: false,
            myObject: null,
            contextmenu: null,
            scope: null,
            myEvent: null,
            options: [
                {
                    title: "Copy", icon: "files", func: () => {
                        console.log("copyyyyyyyyy" + this.myObject.title)
                        // navigator.clipboard.writeText(this.myObject.content);
                    }
                },
                {
                    title: "Pin", icon: "pin", func: () => {
                        this.al()
                    }
                },
                {
                    title: "Select", icon: "check-circle", func: () => {
                        event.$emit("showSelector", this.myObject);
                    }
                },
                {
                    title: "Delete", icon: "trash", color: "danger", func: () => {
                        event.$emit("myDelete", this.myObject);
                    }
                },
            ]
        }
    },
    created() {
        event.$on("showContext", res => {
            this.reset()
            this.visibility = true
            this.contextmenu = document.getElementById("context-menu");
            // this.contextmenu = this.$refs["contextmenu"];
            this.scope = document.getElementById(res.id);
            this.myObject = res.data;
            this.myEvent = res.e;
            this.showContextMenu()
        });
    },
    methods: {
        normalizePozition(mouseX, mouseY) {
            // ? compute what is the mouse position relative to the container element (this.scope)
            const {
                left: scopeOffsetX,
                top: scopeOffsetY,
            } = this.scope.getBoundingClientRect();

            const scopeX = mouseX - scopeOffsetX;
            const scopeY = mouseY - scopeOffsetY;

            // ? check if the element will go out of bounds
            const outOfBoundsOnX =
                scopeX + this.contextmenu.clientWidth > this.scope.clientWidth;

            const outOfBoundsOnY =
                scopeY + this.contextmenu.clientHeight > this.scope.clientHeight;

            let normalizedX = mouseX;
            let normalizedY = mouseY;

            // ? normalzie on X
            if (outOfBoundsOnX) {
                normalizedX =
                    scopeOffsetX + this.scope.clientWidth - this.contextmenu.clientWidth;
            }

            // ? normalize on Y
            if (outOfBoundsOnY) {
                normalizedY =
                    scopeOffsetY + this.scope.clientHeight - this.contextmenu.clientHeight;
            }

            return {normalizedX, normalizedY};
        },
        showContextMenu() {
            this.myEvent.preventDefault();
            const {x: mouseX, y: mouseY} = this.myEvent;

            const {normalizedX, normalizedY} = this.normalizePozition(mouseX, mouseY);

            this.contextmenu.style.top = `${normalizedY}px`;
            this.contextmenu.style.left = `${normalizedX}px`;

            this.contextmenu.classList.remove("visible");

            setTimeout(() => {
                this.contextmenu.classList.add("visible");
            });
        },
        hideContextMenu() {
            if (this.myEvent.target.offsetParent != this.contextmenu) {
                this.contextmenu.classList.remove("visible");
                this.reset();
            }
        },
        reset() {
            this.visibility = false
            this.myObject = null;
            this.contextmenu = null;
            this.scope = null;
            this.myEvent = null;
        },

    },
    directives: {
        click_away: {
            bind(el, binding, vnode) {
                el.clickOutsideEvent = function (event) {
                    // here I check that click was outside the el and his children
                    if (!(el == event.target || el.contains(event.target))) {
                        // and if it did, call method provided in attribute value
                        // vnode.context[binding.expression](event); // use this or binding.value();
                        binding.value();
                    }
                }
                document.body.addEventListener('click', el.clickOutsideEvent)
            },
            unbind(el) {
                document.body.removeEventListener('click', el.clickOutsideEvent)
            }
        }

    },
}
</script>

<style scoped>
html,
body {
    width: 100%;
    height: 100%;
    font-family: "Open Sans", sans-serif;
    padding: 0;
    margin: 0;
}

#context-menu .item {
    padding: 8px 10px;
    font-size: 15px;
    color: #1c1515;
    cursor: pointer;
    border-radius: inherit;
}

#context-menu .item:hover {
    background: #e0e5e0;
}

#context-menu {
    position: fixed;
    z-index: 10000;
    width: 150px;
    background: #ffffff;
    border-radius: 5px;
    transform: scale(0);
    transform-origin: top left;
}

#context-menu.visible {
    display: block;
    transform: scale(1);
    transition: transform 200ms ease-in-out;
}


</style>
