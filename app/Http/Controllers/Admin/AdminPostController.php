<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Comment;
use App\Models\Reply;
use Illuminate\Support\Facades\Auth;
use App\Mail\Websitemail;

class AdminPostController extends Controller
{
    public function index()
    {
        $post_categories = PostCategory::orderBy('name','asc')->get();
        $posts = Post::orderBy('id','desc')->get();
        return view('admin.post.index',compact('posts', 'post_categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|alpha_dash|max:255',
            'description' => 'required|string',
        ]);

        $obj = new Post();
        if($request->hasFile('image')) {
            $final_name = 'post_'.time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads'), $final_name);
            $obj->image = $final_name;
        }
        
        $obj->post_category_id = $request->post_category_id;
        $obj->title = $request->title;
        $obj->title_fa = $request->title_fa;
        $obj->slug = resolve_slug('posts', $request->slug, $request->title, $obj->id);
        $obj->slug_fa = resolve_slug_fa('posts', $request->slug_fa, $request->title_fa, $obj->id);
        $obj->excerpt = $request->excerpt;
        $obj->excerpt_fa = $request->excerpt_fa;
        $obj->description = $request->description;
        $obj->description_fa = $request->description_fa;
        $obj->seo_title = $request->seo_title;
        $obj->seo_title_fa = $request->seo_title_fa;
        $obj->seo_meta_description = $request->seo_meta_description;
        $obj->seo_meta_description_fa = $request->seo_meta_description_fa;
        $obj->show_on_home = $request->show_on_home ?: 'Yes';

        // remove duplicate tags and remove extra spaces between tags
        $tags_array = explode(',', $request->tags);
        $tags_array = array_map('trim', $tags_array);
        $tags_array = array_unique($tags_array);
        $tags_string = implode(',', $tags_array);
        $obj->tags = $tags_string;

        $obj->save();

        return redirect()->back()->with('success', 'Item created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|alpha_dash|max:255',
            'description' => 'required|string',
        ]);

        $obj = Post::findOrFail($id);
        if($request->hasFile('image')) {
            $request->validate([
                'image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $final_name = 'post_'.time().'.'.$request->image->extension();
            if($obj->image && file_exists(public_path('uploads/'.$obj->image))) {
                unlink(public_path('uploads/'.$obj->image));
            }
            $request->image->move(public_path('uploads'), $final_name);
            $obj->image = $final_name;
        }
        $obj->post_category_id = $request->post_category_id;
        $obj->title = $request->title;
        $obj->title_fa = $request->title_fa;
        $obj->slug = resolve_slug('posts', $request->slug, $request->title, $obj->id);
        $obj->slug_fa = resolve_slug_fa('posts', $request->slug_fa, $request->title_fa, $obj->id);
        $obj->excerpt = $request->excerpt;
        $obj->excerpt_fa = $request->excerpt_fa;
        $obj->description = $request->description;
        $obj->description_fa = $request->description_fa;
        $obj->seo_title = $request->seo_title;
        $obj->seo_title_fa = $request->seo_title_fa;
        $obj->seo_meta_description = $request->seo_meta_description;
        $obj->seo_meta_description_fa = $request->seo_meta_description_fa;
        $obj->show_on_home = $request->show_on_home ?: 'Yes';

        // remove duplicate tags and remove extra spaces between tags
        $tags_array = explode(',', $request->tags);
        $tags_array = array_map('trim', $tags_array);
        $tags_array = array_unique($tags_array);
        $tags_string = implode(',', $tags_array);
        $obj->tags = $tags_string;

        $obj->save();

        return redirect()->back()->with('success', 'Item updated successfully.');
    }

    public function delete($id)
    {
        Comment::where('post_id', $id)->delete();
        Reply::where('post_id', $id)->delete();

        $obj = Post::findOrFail($id);
        if($obj->image && file_exists(public_path('uploads/'.$obj->image))) {
            unlink(public_path('uploads/'.$obj->image));
        }
        $obj->delete();

        return redirect()->back()->with('success', 'Item deleted successfully.');
    }

    public function comments()
    {
        $comments = Comment::orderBy('id','desc')->get();
        return view('admin.post.comment', compact('comments'));
    }

    public function change_comment_status(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $comment->status = $request->status;
        $comment->save();

        $subject = "Comment Status Changed";
        $message = "Your comment status has been changed to ".$comment->status." for this post:<br>";
        $message .= "Post Title: ".$comment->post->title."<br>";
        $message .= "<a href='".route('post', $comment->post->slug)."'>See Post</a><br>";

        \safe_mail($comment->email, $subject, $message);

        return back()->with('success', 'Comment status changed successfully.');
    }

    public function delete_comment(Request $request, $id)
    {
        Reply::where('comment_id', $id)->delete();

        $comment = Comment::findOrFail($id);
        $comment->delete();

        return back()->with('success', 'Comment deleted successfully.');
    }

    public function replies()
    {
        $replies = Reply::orderBy('id','desc')->get();
        return view('admin.post.reply', compact('replies'));
    }

    public function change_reply_status(Request $request, $id)
    {
        $reply = Reply::findOrFail($id);
        $reply->status = $request->status;
        $reply->save();

        $subject = "Reply Status Changed";
        $message = "Your reply status has been changed to ".$reply->status." for this post:<br>";
        $message .= "Post Title: ".$reply->post->title."<br>";
        $message .= "<a href='".route('post', $reply->post->slug)."'>See Post</a><br>";

        \safe_mail($reply->email, $subject, $message);

        return back()->with('success', 'Reply status changed successfully.');
    }

    public function delete_reply(Request $request, $id)
    {
        $reply = Reply::findOrFail($id);
        $reply->delete();

        return back()->with('success', 'Reply deleted successfully.');
    }

    public function admin_reply(Request $request)
    {
        $request->validate([
            'reply' => 'required|string',
        ]);

        $reply = new Reply();
        $reply->post_id = $request->post_id;
        $reply->comment_id = $request->comment_id;
        $reply->name = Auth::guard('admin')->user()->name;
        $reply->email = Auth::guard('admin')->user()->email;
        $reply->reply = $request->reply;
        $reply->reply_by = 'Admin';
        $reply->status = 'Approved';
        $reply->save();

        return back()->with('success', 'Replied to comment successfully.');
    }
}
