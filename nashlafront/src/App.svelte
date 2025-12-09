<script>
  import { notes, loading, error, success, fetchNotes, addNote, editNote, deleteNote } from './stores/notesStore.js';
  import NoteList from './components/NoteList.svelte';
  import NoteForm from './components/NoteForm.svelte';
  import { onMount } from 'svelte';
  import Alert from './components/Alert.svelte';
  let title = '';
  let content = '';
  let editMode = false;
  let noteToEdit = null;
  let page = 1;
  const pageSize = 8;
  fetchNotes();
  $: totalPages = Math.max(1, Math.ceil($notes.length / pageSize));
  $: pagedNotes = $notes.slice((page - 1) * pageSize, (page - 1) * pageSize + pageSize);
  $: if (page > totalPages) page = totalPages;
  function goToPage(p) { if (p >= 1 && p <= totalPages) page = p; }
  function nextPage() { if (page < totalPages) page += 1; }
  function prevPage() { if (page > 1) page -= 1; }
  function handleAddOrEdit() {
    if (editMode && noteToEdit) { editNote(noteToEdit.id, title, content); editMode = false; noteToEdit = null; }
    else { addNote(title, content); }
    title = ''; content = '';
  }
  function handleEdit(note) { editMode = true; noteToEdit = note; title = note.title; content = note.content; }
  function handleDelete(id) { deleteNote(id); editMode = false; noteToEdit = null; title=''; content=''; }
</script>
<main>
  <div class="header">
    <h1><i class="fa-solid fa-heart"></i> Nashla Notes</h1>
  </div>
  <NoteForm bind:title bind:content loading={$loading} editMode={editMode} onSubmit={handleAddOrEdit} />
  <div class="alerts">
    {#if $error}<Alert type="error" message={$error} on:close={() => error.set('')} />{/if}
    {#if $success}<Alert type="success" message={$success} autoDismiss={4000} on:close={() => success.set('')} />{/if}
  </div>
  {#if $loading}<p class="loading">Cargando...</p>{/if}
  <NoteList notes={pagedNotes} onDelete={handleDelete} onEdit={handleEdit} />
  {#if totalPages > 1}
  <div class="pagination">
    <button class="page-btn" on:click={prevPage} disabled={page === 1} title="Anterior"><i class="fa-solid fa-chevron-left"></i></button>
    {#each Array(totalPages) as _, i}
      <button class="page-num {page === i + 1 ? 'active' : ''}" on:click={() => goToPage(i + 1)}>{i + 1}</button>
    {/each}
    <button class="page-btn" on:click={nextPage} disabled={page === totalPages} title="Siguiente"><i class="fa-solid fa-chevron-right"></i></button>
  </div>
  {/if}
</main>
<style>
main { max-width: 1000px; margin: 0 auto; display: flex; flex-direction: column; gap: 1rem; }
.header { display: flex; align-items: center; justify-content: space-between; }
.header h1 { color: var(--primary-600); display: inline-flex; align-items: center; gap: .6rem; }
.alerts { display: flex; flex-direction: column; gap: .5rem; }
.loading { color: var(--primary-600); }
.pagination { display: flex; gap: .4rem; align-items: center; justify-content: center; }
.page-btn { border: 1px solid var(--border); background: #fff; color: var(--primary-600); border-radius: 999px; padding: .4rem .6rem; }
.page-num { border: 1px solid var(--border); background: #fff; color: var(--primary-600); border-radius: 10px; padding: .3rem .6rem; }
.page-num.active { background: #fff0f6; }
@media (max-width: 480px) { main { padding: 0 .25rem; } }
</style>
