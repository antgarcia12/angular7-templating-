import {Component, OnInit} from "@angular/core";
import {User} from "../shared/interfaces/user";
import {UserService} from "../shared/services/user.service";

@Component({
	template: require("./splash.component.html")
})

export class SplashComponent implements OnInit{

	users : User[];
	constructor( protected userService: UserService) {}

	ngOnInit() {
		this.getUsers()
	}

	getUsers() {
		this.userService.getAllUsers().subscribe(users => this.users = users);
	}
}