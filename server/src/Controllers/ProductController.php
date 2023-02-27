<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Product;

class ProductController
{
    private ?string $requestMethod;

    private ?int $productId;

    private ?string $subRoute;

    private ?string $subValue;

    public function __construct(
        string $requestMethod,
        ?int $productId,
        ?string $subRoute,
        ?string $subValue
    ) {
        $this->requestMethod = $requestMethod;
        $this->productId = $productId;
        $this->subRoute = $subRoute;
        $this->subValue = $subValue;
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->productId) {
                    $this->show($this->productId);
                } elseif ($this->subRoute && $this->subValue) {
                    $this->isUnique($this->subRoute, $this->subValue);
                } else {
                    $this->index();
                }
                break;
            case 'POST':
                if (isset(getallheaders()['x-delete'])) {
                    $this->destroy($this->productId);
                } else {
                    $this->store();
                }
                break;
            case 'PUT':
                $this->update($this->productId);
                break;
            case 'DELETE':
                $this->destroy($this->productId);
                break;
            default:
                $this->notFoundResponse();
                break;
        }
    }

    private function index()
    {
        $result = Product::findAll();
        send(200, $result);
    }

    private function show($id)
    {
        $result = Product::findById($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        send(200, $result);
    }

    private function store()
    {
        $input = (array) json_decode(file_get_contents('php://input'), true);
        // return send(201, $input);
        if (! $this->validateRequest($input)) {
            return $this->unprocessableEntityResponse();
        }
        $newProduct = Product::save(...$this->fixTypes($input));
        send(201, $newProduct());
    }

    private function isUnique($segment, $value)
    {
        $result = null;
        if ($segment === 'sku') {
            $result = Product::findBySku($value);
        } else {
            return $this->notFoundResponse();
        }

        if ($result) {
            $result = false;
            sendError(409, [
                'unique' => $result,
                'message' => 'sku must be unique',
            ]);
        } else {
            $result = true;
            send(200);
        }
    }

    private function update(int $id)
    {
        $result = Product::findById($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), true);
        if (! $this->validateRequest($input)) {
            return $this->unprocessableEntityResponse();
        }
        $updatedProduct = Product::update($id, $input);
        send(200);
    }

    private function destroy(?int $id)
    {
        $result = null;
        $input = '';
        if ($id) {
            $result = Product::findById($id);
            Product::removeOne($id);
        } else {
            $input = (array) json_decode(file_get_contents('php://input'), true);
            if (! isset($input['ids'])) {
                $result = null;
            } else {
                $ids = $input['ids'];
                [$where, $keys] = placeHolderSeq(count($ids), 'id');
                Product::setWhere($where);
                if (count($ids) > 0) {
                    $result = Product::remove(array_combine($keys, $ids));
                }
            }
        }
        send(204);
    }

    private function validateRequest($input)
    {
        if (! isset($input['sku'])) {
            return false;
        }
        if (! isset($input['name'])) {
            return false;
        }
        if (! isset($input['price'])) {
            return false;
        }
        if (! isset($input['type'])) {
            return false;
        }
        if (! isset($input['attribute'])) {
            return false;
        }

        $sku = $input['sku'];
        $name = $input['name'];
        $price = $input['price'];
        $type = $input['type'];
        $attribute = $input['attribute'];

        if (strlen($sku) !== 8) {
            return false;
        }
        if (strlen($name) < 2 || strlen($name) > 150) {
            return false;
        }
        if (! is_numeric($price) || floatval($price) <= 0) {
            return false;
        }
        if (! is_numeric($type)) {
            return false;
        }

        $type = (int) $type;
        if ($type === 0) {
            if (! is_numeric($attribute) || floatval($attribute) <= 0) {
                return false;
            }
        }
        if ($type === 1) {
            if (! is_numeric($attribute) || intval($attribute) <= 0) {
                return false;
            }
        }
        if ($type === 2) {
            if (substr_count($attribute, 'x') !== 2) {
                return false;
            }
            $values = explode('x', $attribute);
            foreach ($values as $v) {
                if (! is_numeric($v) || floatval($v) <= 0) {
                    return false;
                }
            }
        }
        return true;
    }

    private function unprocessableEntityResponse()
    {
        sendError(422);
    }

    private function notFoundResponse()
    {
        sendError(404);
    }

    private function fixTypes($input)
    {
        $sku = strtoupper($input['sku']);
        $name = $input['name'];
        $price = $input['price'];
        $type = $input['type'];
        $attribute = $input['attribute'];

        $type = (int) $type;
        if ($type === 0) {
            $attribute = (float) $attribute;
        }
        if ($type === 1) {
            $attribute = (int) $attribute;
        }
        return [$sku, $name, (float) $price, $type, $attribute];
    }
}
