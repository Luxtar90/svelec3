let defaultUrl = 'http://localhost/backend/api/notes.php';
if (typeof window !== 'undefined' && window.location) {
  const { origin, hostname, port } = window.location;
  if (hostname === 'localhost' && port === '8080') {
    defaultUrl = 'http://localhost/backend/api/notes.php';
  } else {
    defaultUrl = `${origin}/api/notes.php`;
  }
}

export const API_URL = (typeof window !== 'undefined' && window.__API_URL__) || defaultUrl;
