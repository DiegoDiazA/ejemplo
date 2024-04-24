import { Component } from '@angular/core';
import { ApiService } from 'src/app/services/api.service';
import { Pokemon } from 'src/app/interfaces';

@Component({
  selector: 'app-tab2',
  templateUrl: 'tab2.page.html',
  styleUrls: ['tab2.page.scss'],
})
export class Tab2Page {
  // Propiedades para almacenar los datos del nuevo Pokémon
  public nuevoPokemon: Pokemon = {
    id_pkmn: 0, // Inicializamos id_pkmn con un valor predeterminado
    nombre_pkmn: '',
    tipo1: '',
    tipo2: '',
    mov1: '',
    mov2: '',
    mov3: '',
    mov4: '',
    ruta: ''
  };

  constructor(private apiService: ApiService) {}

  // Método para enviar los datos del nuevo Pokémon al servidor
  agregarPokemon() {
    // Llama al método crearPokemon() del servicio ApiService para agregar un nuevo Pokémon
    this.apiService.crearPokemon(this.nuevoPokemon).subscribe(
      (resp) => {
        console.log(resp);
        // Aquí puedes manejar la respuesta del servidor como desees
      },
      (error) => {
        console.error('Error al agregar el nuevo Pokémon:', error);
        // Aquí puedes manejar el error de manera más específica
      }
    );
  }
}
