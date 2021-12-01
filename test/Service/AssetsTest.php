<?php

use PHPUnit\Framework\TestCase;

use App\Service\AssetService;
use Psr\Http\Message\UploadedFileInterface;
use Laminas\Diactoros\Stream;

class AssetsTest extends TestCase
{

    /**
     * @todo for some reason, this doesn't run on the build-agent
     */
    // public function testSave()
    // {
    //     $uploadedFile = new class implements UploadedFileInterface
    //     {
    //         private $image = '/9j/4QAYRXhpZgAASUkqAAgAAAAAAAAAAAAAAP/sABFEdWN' .
    //             'reQABAAQAAABkAAD/4QMuaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLwA' .
    //             '8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN' .
    //             '6a2M5ZCI/PiA8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB' .
    //             '4OnhtcHRrPSJBZG9iZSBYTVAgQ29yZSA1LjYtYzE0OCA3OS4xNjQwMzYsIDI' .
    //             'wMTkvMDgvMTMtMDE6MDY6NTcgICAgICAgICI+IDxyZGY6UkRGIHhtbG5zOnJ' .
    //             'kZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5' .
    //             'zIyI+IDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiIHhtbG5zOnhtcD0' .
    //             'iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIgeG1sbnM6eG1wTU09Imh' .
    //             '0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJ' .
    //             'odHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWY' .
    //             'jIiB4bXA6Q3JlYXRvclRvb2w9IkFkb2JlIFBob3Rvc2hvcCAyMS4wIChNYWN' .
    //             'pbnRvc2gpIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkVBNTBGNjZGNDA' .
    //             'zRDExRUM4RTE1RDUxRDU1QTFBMzdFIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAu' .
    //             'ZGlkOkVBNTBGNjcwNDAzRDExRUM4RTE1RDUxRDU1QTFBMzdFIj4gPHhtcE1NO' .
    //             'kRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6RUE1MEY2Nk' .
    //             'Q0MDNEMTFFQzhFMTVENTFENTVBMUEzN0UiIHN0UmVmOmRvY3VtZW50SUQ9Inh' .
    //             'tcC5kaWQ6RUE1MEY2NkU0MDNEMTFFQzhFMTVENTFENTVBMUEzN0UiLz4gPC9y' .
    //             'ZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY' .
    //             '2tldCBlbmQ9InIiPz7/7gAOQWRvYmUAZMAAAAAB/9sAhAABAQEBAQEBAQEBAQ' .
    //             'EBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAgICAgICAgICAgIDAwM' .
    //             'DAwMDAwMDAQEBAQEBAQIBAQICAgECAgMDAwMDAwMDAwMDAwMDAwMDAwMDAwMD' .
    //             'AwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwP/wAARCAAKAAoDAREAAhEBAxEB/' .
    //             '8QAYgAAAwEAAAAAAAAAAAAAAAAAAAgJCgEBAAAAAAAAAAAAAAAAAAAAABAAAA' .
    //             'QCBwkAAAAAAAAAAAAABAYHCAU3AAMVVzgJGWU2ZqbGRzk6ShEBAAAAAAAAAAA' .
    //             'AAAAAAAAAAP/aAAwDAQACEQMRAD8AZtn7LmXk5izVLDbOyA+JgdGwMwPL2nXK' .
    //             '001KVoWXLbNTg23QZX1CUmsPSjIqeySt5fUISOBxoSBO8ZGCWw1Z5hZ7NMNjy' .
    //             'NC4UXS2EYdLhidxn1+6XEzViwJ3GTB5m3x2tQDK4wJoZ7fvc3xcYE5xKDIzqb' .
    //             'jG1qBkCoH/2Q==';
    //         /**
    //          * Retrieve a stream representing the uploaded file.
    //          *
    //          * This method MUST return a StreamInterface instance, representing the
    //          * uploaded file. The purpose of this method is to allow utilizing native PHP
    //          * stream functionality to manipulate the file upload, such as
    //          * stream_copy_to_stream() (though the result will need to be decorated in a
    //          * native PHP stream wrapper to work with such functions).
    //          *
    //          * If the moveTo() method has been called previously, this method MUST raise
    //          * an exception.
    //          *
    //          * @return StreamInterface Stream representation of the uploaded file.
    //          * @throws \RuntimeException in cases when no stream is available or can be
    //          *     created.
    //          */
    //         public function getStream() {
    //             $resource = fopen('php://memory', 'r+');
    //             fwrite($resource, base64_decode($this->image));
    //             fseek($resource, 0);
    //             return new Stream($resource, 'r+');
    //         }

    //         /**
    //          * Move the uploaded file to a new location.
    //          *
    //          * Use this method as an alternative to move_uploaded_file(). This method is
    //          * guaranteed to work in both SAPI and non-SAPI environments.
    //          * Implementations must determine which environment they are in, and use the
    //          * appropriate method (move_uploaded_file(), rename(), or a stream
    //          * operation) to perform the operation.
    //          *
    //          * $targetPath may be an absolute path, or a relative path. If it is a
    //          * relative path, resolution should be the same as used by PHP's rename()
    //          * function.
    //          *
    //          * The original file or stream MUST be removed on completion.
    //          *
    //          * If this method is called more than once, any subsequent calls MUST raise
    //          * an exception.
    //          *
    //          * When used in an SAPI environment where $_FILES is populated, when writing
    //          * files via moveTo(), is_uploaded_file() and move_uploaded_file() SHOULD be
    //          * used to ensure permissions and upload status are verified correctly.
    //          *
    //          * If you wish to move to a stream, use getStream(), as SAPI operations
    //          * cannot guarantee writing to stream destinations.
    //          *
    //          * @see http://php.net/is_uploaded_file
    //          * @see http://php.net/move_uploaded_file
    //          * @param string $targetPath Path to which to move the uploaded file.
    //          * @throws \InvalidArgumentException if the $targetPath specified is invalid.
    //          * @throws \RuntimeException on any error during the move operation, or on
    //          *     the second or subsequent call to the method.
    //          */
    //         public function moveTo($targetPath)
    //         {
    //         }

    //         /**
    //          * Retrieve the file size.
    //          *
    //          * Implementations SHOULD return the value stored in the "size" key of
    //          * the file in the $_FILES array if available, as PHP calculates this based
    //          * on the actual size transmitted.
    //          *
    //          * @return int|null The file size in bytes or null if unknown.
    //          */
    //         public function getSize()
    //         {
    //             return 1270;
    //         }

    //         /**
    //          * Retrieve the error associated with the uploaded file.
    //          *
    //          * The return value MUST be one of PHP's UPLOAD_ERR_XXX constants.
    //          *
    //          * If the file was uploaded successfully, this method MUST return
    //          * UPLOAD_ERR_OK.
    //          *
    //          * Implementations SHOULD return the value stored in the "error" key of
    //          * the file in the $_FILES array.
    //          *
    //          * @see http://php.net/manual/en/features.file-upload.errors.php
    //          * @return int One of PHP's UPLOAD_ERR_XXX constants.
    //          */
    //         public function getError()
    //         {
    //             return 0;
    //         }

    //         /**
    //          * Retrieve the filename sent by the client.
    //          *
    //          * Do not trust the value returned by this method. A client could send
    //          * a malicious filename with the intention to corrupt or hack your
    //          * application.
    //          *
    //          * Implementations SHOULD return the value stored in the "name" key of
    //          * the file in the $_FILES array.
    //          *
    //          * @return string|null The filename sent by the client or null if none
    //          *     was provided.
    //          */
    //         public function getClientFilename()
    //         {
    //             return 'image.png';
    //         }

    //         /**
    //          * Retrieve the media type sent by the client.
    //          *
    //          * Do not trust the value returned by this method. A client could send
    //          * a malicious media type with the intention to corrupt or hack your
    //          * application.
    //          *
    //          * Implementations SHOULD return the value stored in the "type" key of
    //          * the file in the $_FILES array.
    //          *
    //          * @return string|null The media type sent by the client or null if none
    //          *     was provided.
    //          */
    //         public function getClientMediaType()
    //         {
    //             return 'image/jpeg';
    //         }
    //     };

    //     $service = new AssetService(__DIR__ . '/');
    //     $file = $service->save($uploadedFile);

    //     //@fixme
    //     unlink(__DIR__ . '/' . $file['name']);

    //     $this->assertIsArray($file);
    //     $this->assertCount(3, $file);
    //     $this->assertEquals(10, $file['height']);
    //     $this->assertEquals(10, $file['width']);
    // }

    /**
     * @dataProvider filenameProvider
     */
    public function testName(string $in, string $ext, string $out)
    {
        $this->assertEquals($out, AssetService::fileName($in, $ext, ''));
    }

    public function filenameProvider()
    {
        return [
            ['name.png', 'jpg', 'name.jpg'],
            ['NamE.png', 'jpg', 'name.jpg'],
            ['this name.png', 'jpg', 'this-name.jpg'],
            ['á sjó.png', 'jpg', 'a-sjo.jpg'],
            ['á sjó?vei=5.png', 'jpg', 'a-sjo-vei-5.jpg'],
        ];
    }
}
