<?php
require_once 'components/layout.php';

?>


<?= layout_open("Home"); ?>

<template x-component="styled-button">
    <span x-data="{label: 'hello world'}">
        <label x-text="label"></label>
        <button>
            <slot></slot>
        </button>
    </span>
</template>




<main x-data="{real: 0}">
    <center>
        <h1>Congratulations, You made it</h1>
    </center>
    <br><br><br><br>
    <div>
        <button @click="$store.main.greet()" @click="count++" x-html="`count is ${$store.main.count}`" x-cloak></button>
        <styled-button @click="real++" x-text="`real is ${real}`" />
    </div>
</main>

<script>
    document.addEventListener('alpine:init', () => {
        let old_count = localStorage.count ? +localStorage.count : 0
        if (isNaN(old_count)) old_count = 0
        Alpine.store('main', {
            count: old_count,
            greet() {
                this.count++
                localStorage.count = this.count.toString()
            }
        })

    })
</script>

<?= layout_close(); ?>