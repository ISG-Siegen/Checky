// Component for displaying the archive as a tree structure and managing user interactions.

import { Component } from '@angular/core';
import { MessageService, TreeNode } from 'primeng/api';
import { TreeNodeExpandEvent } from 'primeng/tree';
import { ArchiveService } from '../api';
import { finalize } from 'rxjs';

@Component({
  selector: 'app-archive',
  templateUrl: './archive.component.html',
  styleUrl: './archive.component.scss',
  providers: [MessageService],
})
export class ArchiveComponent {
  nodes: TreeNode[] = []; // Tree structure for the archive.
  archiveLoading = true; // Loading state for the archive.

  constructor(private archiveService: ArchiveService, private msgService: MessageService) {
    // Loads the initial list of conferences and populates the tree nodes.
    archiveService
      .getAppArchiveGetconferences()
      .pipe(finalize(() => (this.archiveLoading = false)))
      .subscribe(res => {
        res.forEach(val => {
          this.nodes.push({
            key: val.id ?? '',
            label: val.name,
            leaf: false,
            type: 'conference',
            loading: false,
          });
        });
      });
  }

  private setLoading(node: TreeNode, loading: boolean) {
    // Sets the loading state for a node or its parent.
    let parent = node.parent;
    if (parent) {
      parent.loading = loading;
    } else {
      node.loading = loading;
    }
  }

  onNodeExpand(event: TreeNodeExpandEvent) {
    // Handles expanding nodes to load their children dynamically.
    if (event.node.children || !event.node.key) {
      return; // Exit if children are already loaded or node key is missing.
    }

    this.setLoading(event.node, true);

    if (event.node.type == 'conference') {
      // Load instances for a conference.
      this.archiveService.getAppArchiveGetinstances(event.node.key).subscribe(res => {
        event.node.children = res.map(val => ({
          key: val.id ?? '',
          label: val.year.toString(),
          leaf: false,
          type: 'instance',
          loading: false,
        }));
        this.setLoading(event.node, false);
      });
    } else if (event.node.type == 'instance') {
      // Load details for an instance.
      this.archiveService.getAppArchiveGetinstancedetails(event.node.key).subscribe(res => {
        event.node.children = [
          {
            key: res.id ?? '',
            label: res.year.toString(),
            data: res,
            type: 'instanceDetail',
            loading: true,
          },
        ];
        this.setLoading(event.node, false);
      });
    } else {
      console.error('Invalid node type:', event.node.type);
      this.setLoading(event.node, false);
    }
  }

  copyToClipboard(text: string) {
    // Copies the provided text to the clipboard and shows success/error messages.
    navigator.clipboard
      .writeText(text)
      .then(() => {
        this.msgService.add({
          severity: 'success',
          summary: 'Copied!',
          detail: 'Prompt was copied to your clipboard!',
        });
      })
      .catch(() => {
        this.msgService.add({
          severity: 'error',
          summary: 'Error!',
          detail: 'Could not write to your clipboard! Please copy manually.',
        });
      });
  }
}
