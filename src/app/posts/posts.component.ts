import {Component, OnInit} from "@angular/core";
import {Post} from "../shared/interfaces/post";
import {PostService} from "../shared/services/post.service";

@Component({
	template: require("./posts.component.html")
})

export class PostsComponent implements OnInit{
	posts: Post[];

	constructor(private postService: PostService) {}

	ngOnInit() {
		this.postService.getAllPosts().subscribe(posts => this.posts = posts);
	}

}