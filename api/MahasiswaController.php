<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/MahasiswaModel.php';

class MahasiswaController extends Controller {
    private $model;

    public function __construct() {
        $this->model = new MahasiswaModel();
    }

    public function index() {
        $data = $this->model->getAll();
        $this->response($data);
    }

    public function show($id) {
        $data = $this->model->getById($id);
        if ($data) $this->response($data);
        else $this->response(["error" => "Data tidak ditemukan"], 404);
    }

    public function store() {
        $input = json_decode(file_get_contents("php://input"), true);
        $result = $this->model->create($input);
        $this->response($result, 201);
    }

    public function update($id) {
        $input = json_decode(file_get_contents("php://input"), true);
        $result = $this->model->update($id, $input);
        $this->response($result);
    }

    public function destroy($id) {
        $result = $this->model->delete($id);
        $this->response($result);
    }
}
