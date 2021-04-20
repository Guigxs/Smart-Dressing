import { Component, OnInit } from '@angular/core';
import { Cloth, RestService } from '../rest.service';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.scss']
})
export class HomeComponent implements OnInit {

  clothers: Cloth[] = []

  constructor(public rest:RestService, ) { }

  ngOnInit(): void {
    this.getClothers()
  }

  getClothers(){
    this.rest.getClothers().subscribe((resp) => {
      this.clothers = resp
    })
  }

}
