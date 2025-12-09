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
let isDark = false;
let page = 1;
const pageSize = 8;

fetchNotes();

onMount(() => {
  const saved = localStorage.getItem('theme');
  isDark = saved === 'dark';
});

$: document.body.classList.toggle('dark', isDark);

function toggleTheme() {
  isDark = !isDark;
  localStorage.setItem('theme', isDark ? 'dark' : 'light');
}

$: totalPages = Math.max(1, Math.ceil($notes.length / pageSize));
$: pagedNotes = $notes.slice((page - 1) * pageSize, (page - 1) * pageSize + pageSize);
$: if (page > totalPages) page = totalPages;

function goToPage(p) {
  if (p >= 1 && p <= totalPages) page = p;
}
function nextPage() { if (page < totalPages) page += 1; }
function prevPage() { if (page > 1) page -= 1; }

function handleAddOrEdit() {
  if (editMode && noteToEdit) {
    editNote(noteToEdit.id, title, content);
    editMode = false;
    noteToEdit = null;
  } else {
    addNote(title, content);
  }
  title = '';
  content = '';
}

function handleEdit(note) {
  editMode = true;
  noteToEdit = note;
  title = note.title;
  content = note.content;
}

function handleDelete(id) {
  deleteNote(id);
  if (editMode && noteToEdit && noteToEdit.id === id) {
    editMode = false;
    noteToEdit = null;
    title = '';
    content = '';
  }
}
</script>

<main>
	<div class="header">
		<h1><i class="fa-solid fa-note-sticky"></i> Notas</h1>
		<button class="theme-btn" on:click={toggleTheme} title={isDark ? 'Modo día' : 'Modo noche'}>
			{#if isDark}
				<i class="fa-solid fa-sun"></i>
			{:else}
				<i class="fa-solid fa-moon"></i>
			{/if}
		</button>
	</div>
	<NoteForm bind:title bind:content loading={$loading} editMode={editMode} onSubmit={handleAddOrEdit} />
	<div class="alerts">
		{#if $error}
			<Alert type="error" message={$error} on:close={() => error.set('')} />
		{/if}
		{#if $success}
			<Alert type="success" message={$success} autoDismiss={4000} on:close={() => success.set('')} />
		{/if}
	</div>
	{#if $loading}
		<p>Cargando...</p>
	{/if}
	<NoteList notes={pagedNotes} onDelete={handleDelete} onEdit={handleEdit} />

	{#if totalPages > 1}
	<div class="pagination">
		<button class="page-btn" on:click={prevPage} disabled={page === 1} title="Anterior">
			<i class="fa-solid fa-chevron-left"></i>
		</button>
		{#each Array(totalPages) as _, i}
			<button class="page-num {page === i + 1 ? 'active' : ''}" on:click={() => goToPage(i + 1)}>{i + 1}</button>
		{/each}
		<button class="page-btn" on:click={nextPage} disabled={page === totalPages} title="Siguiente">
			<i class="fa-solid fa-chevron-right"></i>
		</button>
	</div>
	{/if}
</main>

<style>
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');

:global(:root) {
    --bg-start: #f8fafc;
    --bg-end: #e0e7ff;
    --text: #1f2937;
    --card-bg: #ffffff;
    --soft-bg: #f1f5f9;
    --border: #cbd5e1;
    --primary: #6366f1;
    --primary-hover: #4338ca;
    --danger: #ef4444;
    --danger-hover: #b91c1c;
    --muted: #64748b;
    --success: #16a34a;
    --success-bg: #dcfce7;
    --error-bg: #fee2e2;
    --info: #0ea5e9;
    --info-bg: #e0f2fe;
}

:global(.dark) {
    --bg-start: #0f172a;
    --bg-end: #1f2937;
    --text: #e5e7eb;
    --card-bg: #111827;
    --soft-bg: #1f2937;
    --border: #374151;
    --primary: #818cf8;
    --primary-hover: #6366f1;
    --danger: #f87171;
    --danger-hover: #ef4444;
    --muted: #9ca3af;
    --success: #22c55e;
    --success-bg: #064e3b;
    --error-bg: #2c1b1b;
    --info: #38bdf8;
    --info-bg: #0c4a6e;
}

:global(body) {
    background: linear-gradient(135deg, var(--bg-start) 0%, var(--bg-end) 100%);
    color: var(--text);
    font-family: 'Roboto', sans-serif;
    margin: 0;
}

main {
    background: var(--card-bg);
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.08);
    padding: 2em;
    max-width: 960px;
    width: 96vw;
    margin: 2em auto;
}

h1 {
    color: var(--primary);
    font-size: 2.5em;
    font-weight: 700;
    margin-bottom: 1em;
    letter-spacing: 2px;
    text-align: left;
}

.header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1em;
}

.theme-btn {
    background: var(--soft-bg);
    color: var(--text);
    border: 1px solid var(--border);
    border-radius: 999px;
    padding: 0.5em 0.75em;
    font-size: 1.1em;
    cursor: pointer;
    transition: background 0.2s;
}
.theme-btn:hover {
    background: var(--primary);
    color: #fff;
}

.alerts { display: flex; flex-direction: column; gap: 0.6em; margin-bottom: 1em; }

.pagination {
    display: flex;
    align-items: center;
    gap: 0.5em;
    justify-content: center;
    margin-top: 1em;
}
.page-btn {
    background: var(--soft-bg);
    color: var(--text);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 0.4em 0.6em;
    cursor: pointer;
}
.page-btn:disabled { opacity: 0.5; cursor: default; }
.page-num {
    background: var(--soft-bg);
    color: var(--text);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 0.4em 0.6em;
    cursor: pointer;
}
.page-num.active {
    background: var(--primary);
    color: #fff;
    border-color: var(--primary);
}


@media (max-width: 720px) {
    h1 { font-size: 2.1em; }
    .header { flex-direction: column; gap: 0.75em; }
}
@media (max-width: 480px) {
    h1 { font-size: 1.8em; letter-spacing: 1px; }
    .pagination { flex-wrap: wrap; }
    .page-num, .page-btn { padding: 0.35em 0.5em; font-size: 0.95em; }
    main { padding: 1em; }
}
</style>
