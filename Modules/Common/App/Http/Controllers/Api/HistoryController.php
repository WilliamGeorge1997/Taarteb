<?php
namespace Modules\Common\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Common\Service\HistoryService;
use Modules\Common\App\resources\HistoryResource;

class HistoryController extends Controller
{
    private $historyService;
    public function __construct(HistoryService $historyService)
    {
        $this->historyService = $historyService;
    }
    public function index(Request $request)
    {
        $data = $request->all();
        $histories = $this->historyService->findAll($data);
        return returnMessage(true, 'Histories fetched successfully', HistoryResource::collection($histories)->response()->getData(true));
    }
}
