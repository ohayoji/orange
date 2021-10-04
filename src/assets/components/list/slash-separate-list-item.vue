<!-- スラッシュ区切りのシンプルなテキストリスト -->
<template>
  <span>
    <span v-if="slashable"> / </span>
    <span>{{text}}</span>
  </span>
</template>

<script>
export default {
  props: [
    //区切りスラッシュ表示フラグ
    'slashable',
    //表示テキスト
    'text'
  ]
}
</script>

<style lang="scss">

</style>
