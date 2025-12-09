<script>
  export let note;
  import { createEventDispatcher } from 'svelte';
  const dispatch = createEventDispatcher();
  function handleDelete() { dispatch('delete'); }
  function handleEdit() { dispatch('edit'); }
  function formatDate(d) { const dt = new Date(d); return dt.toLocaleString('es-ES', { dateStyle: 'long', timeStyle: 'short' }); }
</script>
<li class="note-item">
  <div class="note-header">
    <div class="note-title"><i class="fa-solid fa-feather"></i> {note.title}</div>
    <div class="note-actions">
      <button class="icon-btn edit" title="Editar" on:click={handleEdit}><i class="fa-solid fa-pen"></i></button>
      <button class="icon-btn delete" title="Eliminar" on:click={handleDelete}><i class="fa-solid fa-trash"></i></button>
    </div>
  </div>
  <div class="note-content">{note.content}</div>
  <div class="note-footer">
    <span class="pill"><i class="fa-solid fa-calendar-plus"></i> {formatDate(note.created_at)}</span>
    <span class="pill"><i class="fa-solid fa-calendar-check"></i> {formatDate(note.updated_at)}</span>
  </div>
</li>
<style>
.note-item { background: var(--card-bg); border: 1px solid var(--border); border-left: 6px solid var(--primary); border-radius: 18px; padding: 1rem; box-shadow: 0 8px 24px rgba(233,30,99,.08); display: flex; flex-direction: column; gap: .75rem; }
.note-item:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(233,30,99,.12); }
.note-header { display: flex; align-items: center; justify-content: space-between; }
.note-title { font-weight: 700; color: var(--primary-600); display: inline-flex; align-items: center; gap: .5rem; }
.note-actions { display: inline-flex; gap: .4rem; }
.icon-btn { border: 1px solid var(--border); background: #fff; color: var(--primary-600); border-radius: 999px; padding: .4rem .6rem; }
.icon-btn.delete { color: #9b2226; }
.note-content { color: var(--text); line-height: 1.5; }
.note-footer { display: flex; gap: .5rem; flex-wrap: wrap; }
.pill { background: #fff0f6; color: var(--primary-600); border: 1px solid var(--border); border-radius: 999px; padding: .25rem .6rem; font-size: .85rem; display: inline-flex; align-items: center; gap: .35rem; }
</style>
