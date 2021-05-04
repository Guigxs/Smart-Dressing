import { Component, OnInit, Input, OnDestroy } from '@angular/core';
import { Cloth, RestService } from '../rest.service';
import { ActivatedRoute, NavigationEnd, Router } from '@angular/router';

var a = 0

@Component({
  selector: 'app-clothers-list',
  templateUrl: './clothers-list.component.html',
  styleUrls: ['./clothers-list.component.scss'],
})
export class ClothersListComponent implements OnInit, OnDestroy {
  @Input() clothers: Cloth[];
  navigationSubscription;

  constructor(private router: Router, public rest:RestService, private route: ActivatedRoute) { 
    this.navigationSubscription = this.router.events.subscribe((e: any) => {
      // If it is a NavigationEnd event re-initalise the component
      if (e instanceof NavigationEnd) {
        this.initialiseInvites();
      }
    });
  }

  ngOnInit() {
  }
  
  ngOnDestroy() {
    if (this.navigationSubscription) {  
      this.navigationSubscription.unsubscribe();
   }
  }

  initialiseInvites() {
    this.ngOnInit()
  }

  deleteCloth(id){
    this.rest.deleteCloth(id).subscribe((resp) => {
      this.router.navigate(["tabs/tab1", {"a":a}])
      a += 1
    })
  }

}
