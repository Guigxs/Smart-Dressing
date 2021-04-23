import { Injectable } from '@angular/core';
import { HttpClient, } from '@angular/common/http'
import { Observable, of, } from 'rxjs'
import { catchError, } from 'rxjs/operators';

const endpoint = "http://localhost:8000/api"

export interface Category {
  id: number,
  name: string, 
  temperature: number,
  weather: string,
  rainLevel: string,
}

export interface Cloth {
  id: number,
  name: string, 
  color: string,
  fabric: string,
  quantity: string,
}

export interface Wardrobe {
  id: number,
  user: object
}

@Injectable({
  providedIn: 'root'
})
export class RestService {

  constructor(private http: HttpClient) { }

  getCategories(): Observable<any> {  
    return this.http.get<Category>(endpoint+"/category")
  }
  getWeather(): Observable<any> {  
    return this.http.get(endpoint+"/weather")
  }
  createCategory(data): Observable<any> {
    return this.http.post<any>(endpoint+"/category", data)
  }

  getWardrobe(): Observable<any> {
    return this.http.get<any>(endpoint+"/wardrobe")
  }
  removeWardrobe(id): Observable<any>{
    console.log(endpoint+"/wardrobe/"+id)
    return this.http.delete(endpoint+"/wardrobe/"+id)
  }
  createWardrobe(): Observable<any> {
    console.log(endpoint+"/wardrobe/")
    return this.http.post<any>(endpoint+"/wardrobe", {})
  }

  getClothersForToday(): Observable<any> {
    return this.http.get<Cloth>(endpoint+"/getCloth").pipe(catchError(err=> of([])))
  }
  getClothers(category=null): Observable<any> {
    if (category){
      return this.http.get<Cloth>(endpoint+"/cloth/"+category)
    }
    return this.http.get<Cloth>(endpoint+"/cloth")
  }
  search(text): Observable<any> {
    return this.http.get<Cloth>(endpoint+"/cloth/search/"+text)
  }

  getLocation(): Observable<any> {
    return this.http.get(endpoint+"/location")
  }
  getAllLocations(): Observable<any> {
    return this.http.get(endpoint+"/location/available")
  }
  deleteLocation(): Observable<any> {
    return this.http.delete(endpoint+"/location")
  }
  createLocation(country, city): Observable<any> {
    return this.http.post(endpoint+"/location/"+country+"-"+city, {})
  }
}
