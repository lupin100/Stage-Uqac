import defaultAvatar from '../assets/default-avatar.png'

// permet de gérer les photos de profil
export function usePhoto() {
  const getPhotoUrl = (path) => {
    if (!path) return defaultAvatar;

    if (path.startsWith('http')) return path;

    const baseUrl = import.meta.env.VITE_API_URL;

    if (!path.includes('uploads/persons/')) {
      return `${baseUrl}/uploads/persons/${path}`;
    }

    return `${baseUrl}/${path}`;
  }

  return { getPhotoUrl };
}