import { Component, OnInit, Input } from '@angular/core';
import { Category, RestService } from '../rest.service';
import { ActivatedRoute, NavigationEnd, Router } from '@angular/router';

var a = 0

@Component({
  selector: 'app-categories-list',
  templateUrl: './categories-list.component.html',
  styleUrls: ['./categories-list.component.scss'],
})
export class CategoriesListComponent implements OnInit {
  @Input() categories: Category[];
  constructor(private router: Router, public rest:RestService) { }

  ngOnInit() {
    console.log(this.categories)
  }

  deleteCategory(id){
    console.log(id)
    this.rest.deleteCategory(id).subscribe((resp) => {
      this.router.navigate(["tabs/tab2", {"a":a}])
      a += 1
    })
  }

}
