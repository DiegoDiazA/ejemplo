import { Component, OnInit } from '@angular/core';
import { ApiService } from 'src/app/services/api.service';
import { Pokemon } from '../../interfaces/index';

@Component({
  selector: 'app-tab1',
  templateUrl: 'tab1.page.html',
  styleUrls: ['tab1.page.scss'],
})
export class Tab1Page implements OnInit {
  public pokemones: Pokemon[] = [];

  constructor(private apiService: ApiService) { }

  ngOnInit() {
    this.getPokemones();
  }

  getPokemones() {
    this.apiService.getPokemones().subscribe(
      (data) => {
        this.pokemones = data;
      },
      (error) => {
        console.error('Error al obtener los pokemones', error);
      }
    );
  }

  eliminarPokemon(id_pkmn: number) {
    this.apiService.eliminarPokemon(id_pkmn).subscribe(
      () => {
        console.log('Pokemon eliminado con éxito');
        // Filtrar la lista actual para quitar el elemento eliminado
        this.pokemones = this.pokemones.filter((pokemon) => pokemon.id_pkmn !== id_pkmn);
      },
      (error) => {
        console.error('Error al eliminar el pokemon:', error);
      }
      
    );
    window.location.reload();
  }
  
}
