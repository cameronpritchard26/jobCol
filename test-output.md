
   PASS  Tests\Unit\ExampleTest
  ✓ that true is true                                                    0.19s  

   PASS  Tests\Feature\ExampleTest
  ✓ the application returns a successful response                        1.88s  

   PASS  Tests\Feature\ProfilePictureUploadTest
  ✓ student can upload a profile picture                                 2.55s  
  ✓ employer can upload a profile picture                                0.09s  
  ✓ upload validation rejects non-image files                            0.12s  
  ✓ upload validation rejects files over 5MB                             0.10s  
  ✓ ProcessProfilePicture job resizes converts and stores image          0.34s  
  ✓ ProcessProfilePicture job deletes old picture when uploading new on… 0.18s  
  ✓ DeleteProfilePicture job deletes file and nulls column               0.14s  
  ✓ student can delete their profile picture                             0.11s  
  ✓ employer can delete their profile picture                            0.07s  

  Tests:    11 passed (32 assertions)
  Duration: 7.15s

