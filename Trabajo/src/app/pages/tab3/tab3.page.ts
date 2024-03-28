import { Component } from '@angular/core';
import { AuthService } from '../../services/auth.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-tab3',
  templateUrl: 'tab3.page.html',
  styleUrls: ['tab3.page.scss'],
})
export class Tab3Page {
  username: string = '';
  password: string = '';
  

  constructor(private authService: AuthService, private router: Router) {}

  nextpage() {
    this.router.navigate(['/registro']);
  }

  login() {
    this.router.navigate(['']);
  }
}
