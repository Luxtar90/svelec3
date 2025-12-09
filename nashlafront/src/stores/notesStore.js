import { writable } from 'svelte/store';
import { API_URL } from '../config.js';

export const notes = writable([]);
export const loading = writable(false);
export const error = writable('');
export const success = writable('');

export async function fetchNotes() {
  loading.set(true);
  error.set('');
  try {
    const res = await fetch(API_URL);
    if (!res.ok) {
      let msg = 'Error al cargar notas';
      try { const d = await res.json(); if (d && d.error) msg = d.error; } catch {}
      throw new Error(msg);
    }
    notes.set(await res.json());
  } catch (e) {
    error.set(e.message);
  } finally { loading.set(false); }
}

export async function addNote(title, content) {
  const t = String(title || '').trim();
  const c = String(content || '').trim();
  if (t.length === 0 || c.length === 0) { error.set('Completa título y contenido'); success.set(''); return; }
  if (t.length > 255) { error.set('El título no puede superar 255 caracteres'); success.set(''); return; }
  if (c.length > 5000) { error.set('El contenido no puede superar 5000 caracteres'); success.set(''); return; }
  loading.set(true);
  error.set('');
  success.set('');
  try {
    const res = await fetch(API_URL, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ title: t, content: c })
    });
    if (!res.ok) {
      let msg = 'Error al crear nota';
      try { const d = await res.json(); if (d && d.error) msg = d.error; } catch {}
      throw new Error(msg);
    }
    await fetchNotes();
    success.set('Nota creada correctamente');
  } catch (e) {
    error.set(e.message); success.set('');
  } finally { loading.set(false); }
}

export async function editNote(id, title, content) {
  const t = String(title || '').trim();
  const c = String(content || '').trim();
  if (!id || Number(id) <= 0) { error.set('ID inválido'); success.set(''); return; }
  if (t.length === 0 || c.length === 0) { error.set('Completa título y contenido'); success.set(''); return; }
  if (t.length > 255) { error.set('El título no puede superar 255 caracteres'); success.set(''); return; }
  if (c.length > 5000) { error.set('El contenido no puede superar 5000 caracteres'); success.set(''); return; }
  loading.set(true);
  error.set(''); success.set('');
  try {
    const body = new URLSearchParams({ id: String(id), title: t, content: c });
    const res = await fetch(API_URL, { method: 'PUT', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body });
    if (!res.ok) {
      let msg = 'Error al editar nota';
      try { const d = await res.json(); if (d && d.error) msg = d.error; } catch {}
      throw new Error(msg);
    }
    await fetchNotes();
    success.set('Nota editada correctamente');
  } catch (e) { error.set(e.message); success.set(''); }
  finally { loading.set(false); }
}

export async function deleteNote(id) {
  loading.set(true);
  error.set(''); success.set('');
  try {
    const res = await fetch(`${API_URL}?id=${id}`, { method: 'DELETE' });
    if (!res.ok) {
      let msg = 'Error al eliminar nota';
      try { const d = await res.json(); if (d && d.error) msg = d.error; } catch {}
      throw new Error(msg);
    }
    await fetchNotes();
    success.set('Nota eliminada correctamente');
  } catch (e) { error.set(e.message); success.set(''); }
  finally { loading.set(false); }
}
