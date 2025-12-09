<script>
  import { createEventDispatcher } from 'svelte';
  import { slide, fade } from 'svelte/transition';
  export let type = 'info';
  export let message = '';
  export let autoDismiss = 0;
  const dispatch = createEventDispatcher();
  let timer;
  $: if (autoDismiss > 0 && message) {
    clearTimeout(timer);
    timer = setTimeout(() => dispatch('close'), autoDismiss);
  }
  function close() { dispatch('close'); }
  const icons = {
    success: 'fa-circle-check',
    error: 'fa-circle-xmark',
    info: 'fa-circle-info',
    warning: 'fa-triangle-exclamation'
  };
</script>
<div class={`alert ${type}`} in:slide={{ duration: 150 }} out:fade={{ duration: 120 }}>
  <i class={`fa-solid ${icons[type] || icons.info}`}></i>
  <span class="msg">{message}</span>
  <button class="close" on:click={close} title="Cerrar">
    <i class="fa-solid fa-xmark"></i>
  </button>
</div>
<style>
.alert {
  display: flex;
  align-items: center;
  gap: 0.6em;
  padding: 0.75em 1em;
  border-radius: 10px;
  border: 1px solid var(--border);
  background: var(--soft-bg);
  color: var(--text);
}
.alert.success {
  background: var(--success-bg);
  border-color: var(--success);
  color: var(--success);
}
.alert.error {
  background: var(--error-bg);
  border-color: var(--danger);
  color: var(--danger);
}
.alert.info {
  background: var(--info-bg);
  border-color: var(--info);
  color: var(--info);
}
.alert.warning {
  background: var(--soft-bg);
  border-color: var(--danger);
  color: var(--danger);
}
.alert .close {
  margin-left: auto;
  background: transparent;
  border: none;
  color: inherit;
  font-size: 1.1em;
  cursor: pointer;
}
.msg { flex: 1; }
</style>
