import { Component, OnInit } from '@angular/core';
import { RestService } from './rest.service';
import { Router } from '@angular/router';


@Component({
  selector: 'app-root',
  templateUrl: 'app.component.html',
  styleUrls: ['app.component.scss'],
})
export class AppComponent implements OnInit {
  loading=true
  wardrobe
  location
  constructor(public rest:RestService, public router:Router) {}

  ngOnInit(){
    this.fetchWardrobe()
  }

  fetchWardrobe(){
    this.rest.getWardrobe().subscribe((resp) => {
      if (resp.length == 0){
        this.wardrobe = null
        this.loading = false
      }
      else{
        this.wardrobe = resp
        this.loading = false
      }
    })
  }

  createWardrobe(){
    this.rest.createWardrobe().subscribe(()=> {
      this.router.navigate([""])
    })
  }
}
