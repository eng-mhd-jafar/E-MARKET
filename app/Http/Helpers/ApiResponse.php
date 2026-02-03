<?php

namespace App\Http\Helpers;

class ApiResponse
{
    /**
     * إرجاع استجابة نجاح مع رسالة
     *
     * @param string $message رسالة النجاح
     * @param int $statusCode رمز الحالة
     * @return \Illuminate\Http\JsonResponse
     */
    public static function success($message = 'تمت العملية بنجاح', $statusCode = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message
        ], $statusCode);
    }

    /**
     * إرجاع استجابة نجاح مع بيانات
     *
     * @param array $data البيانات
     * @param string|null $message رسالة النجاح
     * @param int $statusCode رمز الحالة
     * @return \Illuminate\Http\JsonResponse
     */
    public static function successWithData($data, $message = null, $statusCode = 200)
    {
        $response = [
            'success' => true,
            'data' => $data


        ];

        if ($message) {
            $response['message'] = $message;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * إرجاع استجابة خطأ
     *
     * @param string|array $message رسالة الخطأ أو مصفوفة أخطاء
     * @param int $statusCode رمز الحالة
     * @return \Illuminate\Http\JsonResponse
     */

    public static function error($message = 'حدث خطأ أثناء تنفيذ العملية', $statusCode = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], $statusCode);
    }

    /**
     * إرجاع استجابة خطأ مع بيانات إضافية
     *
     * @param string $message رسالة الخطأ
     * @param array $data البيانات الإضافية
     * @param int $statusCode رمز الحالة
     * @return \Illuminate\Http\JsonResponse
     */
    public static function errorWithData($message = 'حدث خطأ أثناء تنفيذ العملية', $data = [], $statusCode = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    /**
     * إرجاع استجابة عدم العثور على بيانات
     *
     * @param string $message رسالة الخطأ
     * @return \Illuminate\Http\JsonResponse
     */
    public static function notFound($message = 'لم يتم العثور على البيانات المطلوبة')
    {
        return self::error($message, 404);
    }

    /**
     * إرجاع استجابة عدم السماح بالوصول
     *
     * @param string $message رسالة الخطأ
     * @return \Illuminate\Http\JsonResponse
     */
    public static function forbidden($message = 'غير مسموح لك بالوصول إلى هذا المورد')
    {
        return self::error($message, 403);
    }

    /**
     * إرجاع استجابة خطأ في المصادقة
     *
     * @param string $message رسالة الخطأ
     * @return \Illuminate\Http\JsonResponse
     */
    public static function unauthorized($message = 'غير مصرح لك بالوصول')
    {
        return self::error($message, 401);
    }

    /**
     * إرجاع استجابة للخطأ في التحقق من البيانات
     *
     * @param array $errors مصفوفة الأخطاء
     * @return \Illuminate\Http\JsonResponse
     */
    public static function validationError($errors)
    {
        return self::error($errors, 422);
    }


}
