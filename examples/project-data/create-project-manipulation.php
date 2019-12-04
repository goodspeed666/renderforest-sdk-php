<?php
require './vendor/autoload.php';
$renderforestClient = new \Renderforest\ApiClient(
  'your-api-key',
  'your-client-id'
);

$newProjectId = $renderforestClient->addProject(701);

$duplicatedProject = $renderforestClient->duplicateProject($newProjectId);

$projectData = $renderforestClient->getProjectData($duplicatedProject);

$firstScreen = $projectData->getScreenByOrder(0);
$firstScreen->setDuration(5);

$videoArea = $firstScreen->getAreaByOrder(0);
$textArea = $firstScreen->getAreaByOrder(1);

$videoArea->setValue('http://techslides.com/demos/sample-videos/small.mp4');
$videoArea->setMimeType('video/mp4');
$videoArea->setFileType('video');
$videoArea->setFileName('small.mp4');

$videoCropParams = new Renderforest\ProjectData\Screen\Area\VideoCropParams\Entity\VideoCropParams();
$videoCropParams->setDuration(5);
$videoCropParams->setTrims([0, 5]);
$videoCropParams->setMime('video/mp4');
$videoCropParams->setThumbnail('http://techslides.com/demos/sample-videos/small.mp4');
$videoCropParams->setThumbnailVideo('http://techslides.com/demos/sample-videos/small.mp4');

$videoCropParamsVolume = new Renderforest\ProjectData\Screen\Area\VideoCropParams\Entity\VideoCropParamsVolume();
$videoCropParamsVolume->setMusic(10);
$videoCropParamsVolume->setVideo(100);

$videoCropParams->setVolume($videoCropParamsVolume);

$videoArea->setVideoCropParams($videoCropParams);
$textArea->setValue('example');

$allSounds = $renderforestClient->getAllSounds(100);
$sound = $allSounds->getSoundById(1980240);
$projectData
    ->getSounds()
    ->add($sound);

$renderforestClient->updateProjectData(23167004, $projectData);

$renderforestClient->renderProject($duplicatedProject, 360);
