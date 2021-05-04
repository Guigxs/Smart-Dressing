import { Component } from '@angular/core';
import { Router } from '@angular/router';

import { RestService } from '../rest.service';

@Component({
  selector: 'app-tab3',
  templateUrl: 'tab3.page.html',
  styleUrls: ['tab3.page.scss']
})
export class Tab3Page {

  constructor(public rest:RestService, public router:Router) {}

  deleteWardrobe(){
    this.rest.deleteAllWardrobe().subscribe(()=>{
      this.router.navigate(["reload"])
    })
  }
}
