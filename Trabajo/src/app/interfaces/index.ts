export interface TopLevel {
  id_mae?: number;
  nombre?: string;
  apodo?:  string;
  tel?:    string;
  foto?:   string;
}

export interface User {
  id_user: string;
  usuario:    string;
  correo:     string;
  contrasena: string;
}

export interface Pokemon {
  id_pkmn: number;
  nombre_pkmn: string;
  tipo1: string;
  tipo2: string;
  mov1: string;
  mov2: string;
  mov3: string;
  mov4: string;
  ruta: string;
}