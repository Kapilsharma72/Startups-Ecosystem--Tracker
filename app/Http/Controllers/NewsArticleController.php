<?php

namespace App\Http\Controllers;

use App\Models\NewsArticle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class NewsArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $newsArticles = NewsArticle::with(['startups', 'investors'])
            ->orderBy('published_at', 'desc')
            ->get();
        return response()->json($newsArticles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|string|max:255',
            'category' => 'required|string|max:255',
            'source' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'published_at' => 'required|date',
            'comments_count' => 'integer',
            'likes_count' => 'integer',
            'shares_count' => 'integer',
            'related_startups' => 'nullable|array',
            'related_investors' => 'nullable|array',
            'tags' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $newsArticle = NewsArticle::create($request->all());
        return response()->json($newsArticle, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(NewsArticle $newsArticle)
    {
        $newsArticle->load('startups', 'investors');
        return response()->json($newsArticle);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NewsArticle $newsArticle)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'image' => 'nullable|string|max:255',
            'category' => 'sometimes|required|string|max:255',
            'source' => 'sometimes|required|string|max:255',
            'url' => 'sometimes|required|string|max:255',
            'published_at' => 'sometimes|required|date',
            'comments_count' => 'integer',
            'likes_count' => 'integer',
            'shares_count' => 'integer',
            'related_startups' => 'nullable|array',
            'related_investors' => 'nullable|array',
            'tags' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $newsArticle->update($request->all());
        return response()->json($newsArticle);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NewsArticle $newsArticle)
    {
        $newsArticle->delete();
        return response()->json(null, 204);
    }

    /**
     * Fetch news from Google News API.
     */
    public function fetchNews(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string',
            'days' => 'integer|min:1|max:30',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $apiKey = config('services.google.news_api_key');
        $days = $request->get('days', 7);
        $fromDate = now()->subDays($days)->format('Y-m-d');

        $response = Http::get('https://newsapi.org/v2/everything', [
            'q' => $request->query,
            'from' => $fromDate,
            'sortBy' => 'publishedAt',
            'language' => 'en',
            'apiKey' => $apiKey,
        ]);

        if ($response->failed()) {
            return response()->json(['error' => 'Failed to fetch news'], 500);
        }

        $articles = collect($response->json()['articles'])->map(function ($article) {
            return [
                'title' => $article['title'],
                'description' => $article['description'],
                'image' => $article['urlToImage'],
                'category' => 'News',
                'source' => $article['source']['name'],
                'url' => $article['url'],
                'published_at' => $article['publishedAt'],
            ];
        });

        return response()->json($articles);
    }
} 