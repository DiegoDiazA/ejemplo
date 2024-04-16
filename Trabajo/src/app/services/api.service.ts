import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { TopLevel, User } from '../interfaces';
import { Observable } from 'rxjs';
import { map } from 'rxjs/operators';
import { Pokemon } from '../interfaces';

@Injectable({
  providedIn: 'root',
})
export class ApiService {
  public apiUrl = 'http://127.0.0.1:80/api1/method.php';
  
  public apiUrl_usuarios = 'http://127.0.0.1:80/api1/method2.php';

  private apiUrl_pokemones = 'http://127.0.0.1:80/api1/method3.php';

  constructor(private http: HttpClient) {}

  getTopHeadlines(): Observable<TopLevel> {
    return this.http
      .get<TopLevel>('http://127.0.0.1:80/api1/method.php')
      .pipe(map((resp) => resp));
  }

   // Método para enviar datos por POST
   postDatos(datos: TopLevel): Observable<any> {
    return this.http.post<any>(this.apiUrl, datos, {
      responseType: 'text' as 'json',
    });
  }

  eliminarDato(id_mae: number): Observable<string> {
    return this.http.delete<string>(`${this.apiUrl}?id_mae=${id_mae}`, {
      responseType: 'text' as 'json',
    });
  }

  //Usuarios
  saveUserData(user: User): Observable<any> {
    const httpOptions = {
      headers: new HttpHeaders({ 'Content-Type': 'application/json' }),
      responseType: 'text' as 'json'
    };
    return this.http.post<any>(`${this.apiUrl_usuarios}`, user, httpOptions);
  }

  getPokemones(): Observable<Pokemon[]> {
    return this.http.get<Pokemon[]>(this.apiUrl_pokemones);
  }

  // Crear un nuevo Pokémon
  crearPokemon(pokemon: Pokemon): Observable<any> {
    return this.http.post<any>(this.apiUrl_pokemones, pokemon);
  }

  // Actualizar un Pokémon existente
  actualizarPokemon(pokemon: Pokemon): Observable<any> {
    return this.http.put<any>(`${this.apiUrl_pokemones}?id_pkmn=${pokemon.id_pkmn}`, pokemon);
  }

  // Eliminar un Pokémon
  eliminarPokemon(id_pkmn: number): Observable<any> {
    return this.http.delete<any>(`${this.apiUrl_pokemones}?id_pkmn=${id_pkmn}`);
  }
  
}
