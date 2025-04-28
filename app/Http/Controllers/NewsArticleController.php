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
     * Fetch news for the frontend from News API.
     */
    public function getNewsApiData(Request $request)
    {
        $apiKey = 'ae643e3d866548ca829938d1097fdee3';
        $query = $request->get('query', 'startup OR technology OR innovation OR "artificial intelligence" OR fintech');
        $category = $request->get('category', null);
        $days = $request->get('days', 7);
        $fromDate = now()->subDays($days)->format('Y-m-d');
        
        $params = [
            'q' => $query,
            'from' => $fromDate,
            'sortBy' => 'publishedAt',
            'language' => 'en',
            'apiKey' => $apiKey,
        ];
        
        if ($category) {
            $params['category'] = $category;
        }
        
        $response = Http::get('https://newsapi.org/v2/everything', $params);
        
        if ($response->failed()) {
            return response()->json(['error' => 'Failed to fetch news'], 500);
        }
        
        $data = $response->json();
        
        if (!isset($data['articles']) || empty($data['articles'])) {
            return response()->json(['error' => 'No articles found'], 404);
        }
        
        $articles = collect($data['articles'])->map(function ($article, $index) {
            // Determine category based on content or default categories
            $possibleCategories = ['AI', 'FinTech', 'Blockchain', 'SaaS', 'HealthTech'];
            $title = strtolower($article['title']);
            
            $category = 'Technology'; // Default
            
            if (str_contains($title, 'ai') || str_contains($title, 'artificial intelligence')) {
                $category = 'AI';
            } elseif (str_contains($title, 'fintech') || str_contains($title, 'finance')) {
                $category = 'FinTech';
            } elseif (str_contains($title, 'blockchain') || str_contains($title, 'crypto')) {
                $category = 'Blockchain';
            } elseif (str_contains($title, 'saas') || str_contains($title, 'software')) {
                $category = 'SaaS';
            } elseif (str_contains($title, 'health') || str_contains($title, 'medical')) {
                $category = 'HealthTech';
            } else {
                // Randomly assign a category if we can't determine one
                $category = $possibleCategories[array_rand($possibleCategories)];
            }
            
            return [
                'id' => $index + 1,
                'title' => $article['title'],
                'description' => $article['description'] ?? 'No description available',
                'image' => $article['urlToImage'] ?? 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                'category' => $category,
                'source' => $article['source']['name'] ?? 'Unknown Source',
                'url' => $article['url'],
                'published_at' => $article['publishedAt'],
                'likes_count' => rand(50, 300),
                'shares_count' => rand(20, 150),
            ];
        });
        
        // Trending topics - could be dynamically generated based on article content
        $trendingTopics = [
            ['id' => 1, 'name' => 'AI', 'count' => rand(30, 50)],
            ['id' => 2, 'name' => 'FinTech', 'count' => rand(30, 45)],
            ['id' => 3, 'name' => 'SaaS', 'count' => rand(25, 40)],
            ['id' => 4, 'name' => 'Blockchain', 'count' => rand(20, 35)],
            ['id' => 5, 'name' => 'HealthTech', 'count' => rand(15, 30)]
        ];
        
        return response()->json([
            'articles' => $articles,
            'featuredArticle' => $articles->first(),
            'trendingTopics' => $trendingTopics
        ]);
    }
}