<script>
  export let title = '';
  export let content = '';
  export let onSubmit;
  export let loading = false;
  export let editMode = false;
  $: tlen = String(title || '').trim().length;
  $: clen = String(content || '').trim().length;
  $: titleError = tlen === 0 ? 'Título requerido' : tlen > 255 ? 'Máximo 255 caracteres' : '';
  $: contentError = clen === 0 ? 'Contenido requerido' : clen > 5000 ? 'Máximo 5000 caracteres' : '';
  $: valid = !titleError && !contentError;
</script>
<form class="form" on:submit|preventDefault={onSubmit}>
  <div class="field">
    <input placeholder="Título" bind:value={title} maxlength="255" class:error={!!titleError} aria-invalid={!!titleError} />
    <div class="field-meta">
      {#if titleError}<span class="field-error">{titleError}</span>{/if}
      <span class="counter">{tlen}/255</span>
    </div>
  </div>
  <div class="field">
    <textarea placeholder="Contenido" bind:value={content} maxlength="5000" rows="4" class:error={!!contentError} aria-invalid={!!contentError}></textarea>
    <div class="field-meta">
      {#if contentError}<span class="field-error">{contentError}</span>{/if}
      <span class="counter">{clen}/5000</span>
    </div>
  </div>
  <button type="submit" class="main-btn" disabled={loading || !valid}>
    {#if editMode}
      <i class="fa-solid fa-floppy-disk"></i> Guardar
    {:else}
      <i class="fa-solid fa-heart-circle-plus"></i> Agregar
    {/if}
  </button>
 </form>
<style>
.form { display: flex; flex-direction: column; gap: .75rem; background: #ffffff; border: 1px solid var(--border); border-radius: 16px; padding: 1rem; }
.field { display: flex; flex-direction: column; gap: .25rem; }
input, textarea { padding: .75rem .9rem; border-radius: 12px; border: 1px solid var(--border); outline: none; }
input.error, textarea.error { border-color: #e25555; }
.field-meta { display: flex; align-items: center; justify-content: space-between; font-size: .85rem; color: #7a5b5b; }
.field-error { color: #b00020; }
.counter { color: #a67a8b; }
.main-btn { align-self: flex-start; display: inline-flex; align-items: center; gap: .5rem; }
</style>
