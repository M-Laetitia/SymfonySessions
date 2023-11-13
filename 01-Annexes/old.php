$programme->setSession($session);
        
        $formStudentSession = $this->createForm(StudentSessionType::class);
        $formStudentSession->handleRequest($request);
 
        if ($formStudentSession->isSubmitted() && $formStudentSession->isValid()) {
            $students = $formStudentSession->getData();

            // Add students to the session
            // get the data from the form. The form handles students, $students should now contain the selected students.
            foreach ($students as $student) {
                $session->addStudent($student);
            }

            $entityManager->persist($session);
            $entityManager->flush();
            return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
        }
 
        return $this->render('session/show.html.twig', [
            'session' => $session,
            'formAddProgramme' => $form,
            'formAddStudent' => $formStudentSession->createView(),
            'formAddStudentSession' => $formStudentSession->createView(),
            'edit' => $programme->getId()
        ]);



    
        $noneRegistered = $sr->findNoneRegistered($session->getId());




        return $this->render('session/show.html.twig', [
            'session' => $session,
            'formAddProgramme' => $form,
            'noneRegistered' => $noneRegistered,
            // 'formAddStudent' => $formStudentSession->createView(),
            // 'formAddStudentSession' => $formStudentSession->createView(),
            'edit' => $programme->getId()
        ]);
