<script>
  import NoteItem from './NoteItem.svelte';
  export let notes = [];
  export let onDelete;
  export let onEdit;
</script>
<ul class="note-grid">
  {#each notes as note}
    <NoteItem {note} on:delete={() => onDelete(note.id)} on:edit={() => onEdit(note)} />
  {/each}
</ul>
<style>
.note-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.1rem; list-style: none; padding: 0; margin: 0; }
@media (max-width: 480px) { .note-grid { grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: .8rem; } }
</style>
