<script>
  export let note;
  import { createEventDispatcher } from 'svelte';
  const dispatch = createEventDispatcher();
  function handleDelete() {
    dispatch('delete');
  }
  function handleEdit() {
    dispatch('edit');
  }
  function formatDate(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleString('es-ES', { dateStyle: 'long', timeStyle: 'short' });
  }
</script>
<li class="note-item">
  <div class="note-header">
    <div class="note-title"><i class="fa-solid fa-note-sticky"></i> {note.title}</div>
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
.note-item {
  background: var(--card-bg);
  border: 1px solid var(--border);
  border-left: 4px solid var(--primary);
  border-radius: 12px;
  margin: 0;
  padding: 1rem;
  box-shadow: 0 2px 10px rgba(0,0,0,0.06);
  display: flex;
  flex-direction: column;
  gap: 0.75em;
  transition: transform .15s ease, box-shadow .15s ease;
}
.note-item:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 18px rgba(0,0,0,0.10);
}
.note-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 0.75em;
}
.note-title {
  font-weight: 700;
  font-size: 1.05em;
  color: var(--primary);
  display: flex;
  align-items: center;
  gap: 0.5em;
}
.note-content {
  color: var(--text);
  font-size: 0.98em;
  line-height: 1.4;
  display: -webkit-box;
  -webkit-line-clamp: 4;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.note-footer {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5em;
}
.pill {
  display: inline-flex;
  align-items: center;
  gap: 0.4em;
  background: var(--soft-bg);
  color: var(--muted);
  border: 1px solid var(--border);
  border-radius: 999px;
  padding: 0.25em 0.6em;
  font-size: 0.85em;
}
.note-actions {
  display: flex;
  gap: 0.4em;
}
.icon-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  border-radius: 10px;
  border: 1px solid var(--border);
  background: var(--soft-bg);
  color: var(--text);
  cursor: pointer;
  transition: background .15s ease, color .15s ease, border-color .15s ease;
}
.icon-btn.edit:hover {
  background: var(--primary);
  color: #fff;
  border-color: var(--primary);
}
.icon-btn.delete:hover {
  background: var(--danger);
  color: #fff;
  border-color: var(--danger);
}
@media (max-width: 480px) {
  .note-item { padding: 0.75em; }
  .note-title { font-size: 1em; }
  .note-content { font-size: 0.95em; }
}
</style>
