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
      <i class="fa-solid fa-floppy-disk"></i> Guardar cambios
    {:else}
      <i class="fa-solid fa-plus"></i> Agregar
    {/if}
  </button>
</form>
<style>
.form {
  display: flex;
  flex-direction: column;
  gap: 0.5em;
  margin-bottom: 1.5em;
}
.field { display: flex; flex-direction: column; gap: 0.25em; }
.field-meta { display: flex; justify-content: space-between; align-items: center; font-size: 0.85em; }
.field-error { color: var(--danger); }
.counter { color: var(--muted); }
input, textarea {
  padding: 0.75em;
  border-radius: 8px;
  border: 1px solid var(--border);
  font-size: 1em;
  font-family: inherit;
  background: var(--soft-bg);
  color: var(--text);
  transition: border 0.2s;
}
input:focus, textarea:focus {
  border-color: var(--primary);
  outline: none;
}
input.error, textarea.error { border-color: var(--danger); }
.main-btn {
  background: var(--primary);
  color: #fff;
  border: none;
  border-radius: 8px;
  padding: 0.75em;
  font-size: 1em;
  font-weight: 700;
  cursor: pointer;
  transition: background 0.2s;
  display: flex;
  align-items: center;
  gap: 0.5em;
  width: 100%;
}
.main-btn:hover {
  background: var(--primary-hover);
}
@media (max-width: 480px) {
  input, textarea { font-size: 0.95em; padding: 0.6em; }
  .main-btn { padding: 0.65em; font-size: 0.95em; }
}
</style>
