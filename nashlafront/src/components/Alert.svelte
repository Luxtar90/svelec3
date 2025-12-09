<script>
  export let type = 'success';
  export let message = '';
  export let autoDismiss = 0;
  import { createEventDispatcher } from 'svelte';
  const dispatch = createEventDispatcher();
  let timer;
  $: if (autoDismiss) { clearTimeout(timer); timer = setTimeout(() => dispatch('close'), autoDismiss); }
</script>
<div class="alert {type}">
  <span class="icon">{#if type==='error'}<i class="fa-solid fa-circle-exclamation"></i>{:else}<i class="fa-solid fa-heart"></i>{/if}</span>
  <span>{message}</span>
  <button class="close" on:click={() => dispatch('close')}><i class="fa-solid fa-xmark"></i></button>
</div>
<style>
.alert { display: flex; align-items: center; gap: .75rem; padding: .75rem 1rem; border-radius: 12px; }
.alert.success { background: #fff0f6; color: var(--primary-600); border: 1px solid var(--border); }
.alert.error { background: #ffe3e3; color: #9b2226; border: 1px solid rgba(155,34,38,.3); }
.icon { display: inline-flex; align-items: center; justify-content: center; }
.close { margin-left: auto; background: transparent; color: inherit; border: none; }
</style>
